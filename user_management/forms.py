from django import forms
from .models import User, UserRole
from django.utils import timezone

from django import forms
from .models import User

class UserForm(forms.ModelForm):
    class Meta:
        model = User
        fields = [
            'first_name', 'last_name', 'father_name', 'gender', 'national_id', 'id_number', 'date_of_birth',
            'province', 'city', 'role', 'work_group', 'status', 'profile_picture', 'phone_type', 
            'phone_number', 'priority', 'username', 'password', 'user_type', 'description',
        ]
        widgets = {
            'date_of_birth': forms.DateInput(attrs={'type': 'date'}),
            'password': forms.PasswordInput(render_value=True),
            'profile_picture': forms.ClearableFileInput(attrs={'class': 'form-control'}),
        }

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

        # Automatically set username to کد ملی (National ID) if not already set
        if not self.instance.pk:  # Only when creating a new user
            self.fields['username'].initial = self.instance.national_id

        # Hide the 'status' field, it's not required to be displayed
        self.fields['status'].widget = forms.HiddenInput()

    def clean_is_staff(self):
        """Automatically check is_staff if the user type is 'ادمین'."""
        user_type = self.cleaned_data.get('user_type')
        if user_type == 'ادمین':
            return True
        return False

    def save(self, commit=True):
        user = super().save(commit=False)
        
        # Automatically set is_staff to True if the user type is 'ادمین'
        if self.cleaned_data.get('user_type') == 'ادمین':
            user.is_staff = True

        # Automatically set the date_joined field if it's not set
        if not user.date_joined:
            user.date_joined = timezone.now()

        if commit:
            user.save()

        return user


class UserRoleForm(forms.ModelForm):
    class Meta:
        model = UserRole
        fields = '__all__'


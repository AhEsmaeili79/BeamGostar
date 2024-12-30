from django.shortcuts import render, get_object_or_404, redirect
from django.views import View
from django.contrib.auth.decorators import login_required
from django.utils.decorators import method_decorator
from django.contrib import messages
from .models import User, UserRole
from .forms import UserForm, UserRoleForm
from django.contrib.auth.mixins import LoginRequiredMixin  # Import LoginRequiredMixin
from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from .models import User
from django.http import HttpResponse

class DashboardView(LoginRequiredMixin, View):
    login_url = '/login'  # Redirect to login if the user is not logged in

    def get(self, request):
        return render(request, 'admin_panel/dashboard.html')

@login_required(login_url='/login')
def user_add_or_edit(request):
    # Get the current user
    user = request.user
    
    if request.method == 'POST':
        # Update user fields based on the submitted data
        user.first_name = request.POST.get('first_name', user.first_name)
        user.last_name = request.POST.get('last_name', user.last_name)
        user.father_name = request.POST.get('father_name', user.father_name)
        user.gender = request.POST.get('gender', user.gender)
        user.national_id = request.POST.get('national_id', user.national_id)
        user.id_number = request.POST.get('id_number', user.id_number)
        user.date_of_birth = request.POST.get('date_of_birth', user.date_of_birth)
        user.province = request.POST.get('province', user.province)
        user.city = request.POST.get('city', user.city)
        user.role_id = request.POST.get('role', user.role.id)  # Assuming roles are passed as their ID
        user.work_group_id = request.POST.get('work_group', user.work_group.id)  # Same for work group
        user.status = request.POST.get('status', user.status)
        user.phone_type = request.POST.get('phone_type', user.phone_type)
        user.phone_number = request.POST.get('phone_number', user.phone_number)
        user.priority = request.POST.get('priority', user.priority)
        user.username = request.POST.get('username', user.username)
        user.password = request.POST.get('password', user.password)
        user.user_type = request.POST.get('user_type', user.user_type)
        user.description = request.POST.get('description', user.description)

        # Handle profile picture (optional)
        if request.FILES.get('profile_picture'):
            user.profile_picture = request.FILES['profile_picture']
        
        user.save()
        return redirect('profile_edit_success')  # Redirect to a success page after save
    
    # Render the page with current user details
    return render(request, 'user_management/user_addoredit.html', {
        'user': user,
    })

from django.shortcuts import render, redirect
from django.contrib.auth import authenticate, login
from django.contrib import messages
from django.http import HttpResponseRedirect

def user_login(request):
    if request.method == 'POST':
        username = request.POST['username']  # Ensure the input name matches this
        password = request.POST['password']
        
        # Authenticate the user
        user = authenticate(request, username=username, password=password)
        
        if user is not None:
            # Log the user in
            login(request, user)
            return redirect('/')  # Redirect to home page after successful login
        else:
            messages.error(request, "Invalid username or password")
            return redirect('admin_panel:login')  # Use the correct namespaced URL for login
            
    return render(request, 'user_management/login.html')
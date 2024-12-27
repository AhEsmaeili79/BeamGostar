from django.contrib import admin
from django.contrib.auth.admin import UserAdmin
from .models import User, UserRole, Menu

@admin.register(UserRole)
class UserRoleAdmin(admin.ModelAdmin):
    """Admin interface for managing user roles and their permissions."""
    list_display = (
        'name', 'status', 'description', 'can_create', 'can_retrieve', 
        'can_update', 'can_delete', 'can_upload', 'can_export'
    )
    search_fields = ('name',)
    list_filter = ('status', 'can_create', 'can_retrieve', 'can_update', 'can_delete', 'can_upload', 'can_export')
    filter_horizontal = ('menus',)  # Allows selection of multiple menus for roles.

class CustomUserAdmin(UserAdmin):
    """Custom admin interface for the User model."""
    list_display = (
        'username', 'first_name', 'last_name', 'email', 'role', 
        'work_group', 'status', 'is_active', 'is_staff'
    )
    search_fields = ('username', 'first_name', 'last_name', 'email', 'national_code')
    list_filter = ('role', 'work_group', 'status', 'is_active', 'is_staff')
    fieldsets = (
        (None, {'fields': ('username', 'password')}),
        ('Personal Info', {
            'fields': (
                'first_name', 'last_name', 'father_name', 'gender', 
                'national_code', 'id_number', 'phone_number', 
                'phone_type', 'profile_picture', 'description'
            )
        }),
        ('Permissions', {'fields': ('is_active', 'is_staff', 'is_superuser', 'groups', 'user_permissions')}),
        ('Important Dates', {'fields': ('last_login', 'date_joined')}),
        ('Roles and Groups', {'fields': ('role', 'work_group', 'priority')}),
        ('Location', {'fields': ('province', 'city')}),
        ('Status', {'fields': ('status',)}),
    )
    add_fieldsets = (
        (None, {
            'classes': ('wide',),
            'fields': ('username', 'password1', 'password2', 'role', 'work_group', 'email', 'status')
        }),
    )
    ordering = ('username',)

# Register the models with the admin site
admin.site.register(User, CustomUserAdmin)

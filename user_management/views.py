from django.shortcuts import render, get_object_or_404, redirect
from django.views import View
from django.contrib.auth.decorators import login_required
from django.utils.decorators import method_decorator
from django.contrib import messages
from .models import User, UserRole
from .forms import UserForm, UserRoleForm

@method_decorator(login_required, name='dispatch')
class DashboardView(View):
    """Admin dashboard view."""
    def get(self, request):
        user_count = User.objects.count()
        role_count = UserRole.objects.count()
        return render(request, 'admin_panel/dashboard.html', {
            'user_count': user_count,
            'role_count': role_count,
        })

@method_decorator(login_required, name='dispatch')
class UserListView(View):
    """List all users."""
    def get(self, request):
        users = User.objects.all()
        return render(request, 'admin_panel/user_list.html', {'users': users})

class UserCreateView(View):
    """Create a new user."""
    def get(self, request):
        form = UserForm()
        return render(request, 'admin_panel/user_form.html', {'form': form})

    def post(self, request):
        form = UserForm(request.POST, request.FILES)
        if form.is_valid():
            user = form.save()  # Automatically saves with the proper fields set
            messages.success(request, 'کاربر با موفقیت ایجاد شد!')
            return redirect('admin_panel:user_list')
        return render(request, 'admin_panel/user_form.html', {'form': form})
    
    
@method_decorator(login_required, name='dispatch')
class UserUpdateView(View):
    """Update an existing user."""
    def get(self, request, pk):
        user = get_object_or_404(User, pk=pk)
        form = UserForm(instance=user)
        return render(request, 'admin_panel/user_form.html', {'form': form, 'user': user})

    def post(self, request, pk):
        user = get_object_or_404(User, pk=pk)
        form = UserForm(request.POST, request.FILES, instance=user)
        if form.is_valid():
            form.save()
            messages.success(request, 'User updated successfully!')
            return redirect('admin_panel:user_list')
        return render(request, 'admin_panel/user_form.html', {'form': form, 'user': user})

@method_decorator(login_required, name='dispatch')
class UserRoleListView(View):
    """List all roles."""
    def get(self, request):
        roles = UserRole.objects.all()
        return render(request, 'admin_panel/role_list.html', {'roles': roles})

@method_decorator(login_required, name='dispatch')
class UserRoleCreateView(View):
    """Create a new role."""
    def get(self, request):
        form = UserRoleForm()
        return render(request, 'admin_panel/role_form.html', {'form': form})

    def post(self, request):
        form = UserRoleForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, 'Role created successfully!')
            return redirect('admin_panel:role_list')
        return render(request, 'admin_panel/role_form.html', {'form': form})


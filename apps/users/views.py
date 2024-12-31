from django.shortcuts import render,redirect
from django.contrib.auth import authenticate, login ,logout
from django.contrib.auth.decorators import login_required
from django.views import View
from django.contrib import messages
from django.contrib.auth.mixins import LoginRequiredMixin 

def user_login(request):
    if request.method == 'POST':
        username = request.POST['username']  
        password = request.POST['password']
        
        # Authenticate the user
        user = authenticate(request, username=username, password=password)
        
        if user is not None:
            # Log the user in
            login(request, user)
            return redirect('/')  
        else:
            messages.error(request, "نام کاربری یا رمز عبور اشتباه است")
            return redirect('login')  
            
    return render(request, 'auth/login.html')

@login_required(login_url='/login')
def user_logout(request):
    # Log the user out
    logout(request)
    # Redirect to the login page
    return redirect('login')


class DashboardView(LoginRequiredMixin, View):
    login_url = '/login' 

    def get(self, request):
        return render(request, 'dashboard/base.html')

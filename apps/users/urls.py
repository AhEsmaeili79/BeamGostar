from django.urls import path
from . import views
from django.conf.urls import handler404, handler500

urlpatterns = [
    path('', views.DashboardView.as_view(), name='dashboard'),
    path('login', views.user_login, name='login'), 
    path('logout/', views.user_logout, name='logout'),
    path('users/add/', views.users_form, name='usersform'),
    path('users/list/', views.users_list, name='userslist'),
    path('accounts/list/', views.accounts_list, name='accounts-list'),
    path('404/', views.error_404, name='askhglks-list'),
    path('user-activity/', views.user_activity, name='user_activity'),
]


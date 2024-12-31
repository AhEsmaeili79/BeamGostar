from django.urls import path
from . import views

urlpatterns = [
    path('', views.DashboardView.as_view(), name='dashboard'),
    path('login', views.user_login, name='login'), 
    path('logout/', views.user_logout, name='logout'),
]
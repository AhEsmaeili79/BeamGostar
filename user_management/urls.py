from django.urls import path
from . import views

app_name = 'admin_panel'

urlpatterns = [
    path('', views.DashboardView.as_view(), name='dashboard'),
    path('users/', views.UserListView.as_view(), name='user_list'),
    path('users/create/', views.UserCreateView.as_view(), name='user_create'),
    path('users/<int:pk>/update/', views.UserUpdateView.as_view(), name='user_update'),
    path('roles/', views.UserRoleListView.as_view(), name='role_list'),
    path('roles/create/', views.UserRoleCreateView.as_view(), name='role_create'),
]

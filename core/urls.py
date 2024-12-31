from django.contrib import admin
from django.urls import path,include


urlpatterns = [
    path('admin/', admin.site.urls),
    path('', include('apps.users.urls')),
    path('', include('apps.analysis.urls')),
    path('', include('apps.customers.urls')),
    path('captcha/', include('captcha.urls')),
]

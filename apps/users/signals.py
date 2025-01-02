from django.contrib.auth.signals import user_logged_in, user_logged_out
from django.dispatch import receiver
from django.utils import timezone
from .models import UserActivity

@receiver(user_logged_in)
def log_user_login(sender, request, user, **kwargs):
    UserActivity.objects.create(
        user=user,
        action_type='logged_in',
        item_name=user.username,
        model_name='User',
        date=timezone.now()
    )

@receiver(user_logged_out)
def log_user_logout(sender, request, user, **kwargs):
    UserActivity.objects.create(
        user=user,
        action_type='logged_out',
        item_name=user.username,
        model_name='User',
        date=timezone.now()
    )

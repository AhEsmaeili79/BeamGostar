from django.db.models.signals import pre_save, pre_delete
from django.dispatch import receiver
from django.contrib.auth.signals import user_logged_in, user_logged_out
from django.dispatch import receiver
from django.utils import timezone
from .models import UserActivity
from .models import UserActivity
from django.contrib.auth.signals import user_logged_in, user_logged_out
from utils.utils import get_persian_datetime

persian_date, persian_time, now  = get_persian_datetime()
datetime = [persian_date, persian_time]

# Log Addition Function
def log_addition(instance, user):
    model_name = instance.__class__.__name__
    
    item_name = str(instance)  # Customize this as needed
    action_type = 'added'  # Default action type for new entries
    if 'deleted' in item_name.lower():
        action_type = 'deleted'
    elif 'changed' in item_name.lower():
        action_type = 'updated'
    elif len(item_name) == 0:
        # If any fields have changed, mark as updated, otherwise, it is added
        action_type = 'added'

    user_activity = UserActivity(
        user=user,
        action_type=action_type,
        item_name=item_name,
        model_name=model_name,
        date=timezone.now(),  # Corrected to call the function
        time= persian_time
    )
    user_activity.save()


# Log any save (create or update) for all models
@receiver(pre_save)
def log_model_save(sender, instance, **kwargs):
    # Avoid logging itself
    if not isinstance(instance, UserActivity):  
        user = getattr(instance, 'user', None)
        
        # Check if the instance is being added or updated
        if instance.pk is None:  # New object is being added (no primary key)
            if user:
                log_addition(instance, user)  # Log the addition
        else:  # The object is being updated (primary key exists)
            if user:
                # Fetch the old instance before the update (for comparison)
                try:
                    old_instance = sender.objects.get(pk=instance.pk)
                    # log_update(instance, user, old_instance)  # Log the update
                except sender.DoesNotExist:
                    log_addition(instance, user)  # If no old instance, treat as addition

# Log any delete for all models

@receiver(user_logged_in)
def log_user_login(sender, request, user, **kwargs):
    UserActivity.objects.create(
        user=user,
        action_type='logged_in',
        item_name="session",
        model_name='User',
        date=timezone.now(),
        time= persian_time
    )

@receiver(user_logged_out)
def log_user_logout(sender, request, user, **kwargs):
    UserActivity.objects.create(
        user=user,
        action_type='logged_out',
        item_name="session",
        model_name='User',
        date=timezone.now(),  # Corrected to call the function
        time= persian_time
    )

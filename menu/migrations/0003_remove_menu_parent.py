# Generated by Django 5.1.2 on 2024-12-27 00:48

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [
        ('menu', '0002_submenu_name'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='menu',
            name='parent',
        ),
    ]
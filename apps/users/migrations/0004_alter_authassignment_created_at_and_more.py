# Generated by Django 5.1.2 on 2025-01-03 03:02

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0003_alter_authassignment_created_at_and_more'),
    ]

    operations = [
        migrations.AlterField(
            model_name='authassignment',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '06:32:00'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '06:32:00'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '06:32:00'], null=True),
        ),
        migrations.AlterField(
            model_name='authitemchild',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '06:32:00']),
        ),
        migrations.AlterField(
            model_name='authmenu',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '06:32:00']),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '06:32:00'], null=True),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '06:32:00'], null=True),
        ),
    ]
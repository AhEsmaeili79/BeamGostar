# Generated by Django 5.1.2 on 2024-12-31 20:15

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0007_alter_authassignment_created_at_and_more'),
    ]

    operations = [
        migrations.AlterField(
            model_name='authassignment',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-11', '23:45:42'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-11', '23:45:42'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-11', '23:45:42'], null=True),
        ),
        migrations.AlterField(
            model_name='authitemchild',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-11', '23:45:42']),
        ),
        migrations.AlterField(
            model_name='authmenu',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-11', '23:45:42']),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-11', '23:45:42'], null=True),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-11', '23:45:42'], null=True),
        ),
        migrations.AlterField(
            model_name='user',
            name='reg_date',
            field=models.DateTimeField(default=['1403-10-11', '23:45:42']),
        ),
        migrations.AlterField(
            model_name='useractivity',
            name='date',
            field=models.DateTimeField(default=['1403-10-11', '23:45:42']),
        ),
    ]
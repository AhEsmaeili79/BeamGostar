# Generated by Django 5.1.2 on 2025-01-02 22:57

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='authassignment',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '02:27:06'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '02:27:06'], null=True),
        ),
        migrations.AlterField(
            model_name='authitem',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '02:27:06'], null=True),
        ),
        migrations.AlterField(
            model_name='authitemchild',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '02:27:06']),
        ),
        migrations.AlterField(
            model_name='authmenu',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '02:27:06']),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='created_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '02:27:06'], null=True),
        ),
        migrations.AlterField(
            model_name='authrule',
            name='updated_at',
            field=models.IntegerField(blank=True, default=['1403-10-14', '02:27:06'], null=True),
        ),
    ]

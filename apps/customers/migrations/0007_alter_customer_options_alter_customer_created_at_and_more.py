# Generated by Django 5.1.2 on 2025-01-03 13:23

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('customers', '0006_alter_customer_created_at_alter_customer_updated_at_and_more'),
    ]

    operations = [
        migrations.AlterModelOptions(
            name='customer',
            options={'ordering': ['-created_at']},
        ),
        migrations.AlterField(
            model_name='customer',
            name='created_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
        migrations.AlterField(
            model_name='customer',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
        migrations.AlterField(
            model_name='paymentmethod',
            name='created_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38'], verbose_name='تاریخ ایجاد'),
        ),
        migrations.AlterField(
            model_name='paymentmethod',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38'], verbose_name='تاریخ ویرایش'),
        ),
        migrations.AlterField(
            model_name='priceanalysis',
            name='created_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
        migrations.AlterField(
            model_name='priceanalysis',
            name='time',
            field=models.TimeField(blank=True, default='16:53:38', null=True, verbose_name='زمان ثبت'),
        ),
        migrations.AlterField(
            model_name='priceanalysis',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
        migrations.AlterField(
            model_name='priceanalysiscredit',
            name='created_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
        migrations.AlterField(
            model_name='priceanalysiscredit',
            name='time',
            field=models.TimeField(blank=True, default='16:53:38', null=True, verbose_name='زمان ثبت'),
        ),
        migrations.AlterField(
            model_name='priceanalysiscredit',
            name='updated_at',
            field=models.DateTimeField(default=['1403-10-14', '16:53:38']),
        ),
    ]
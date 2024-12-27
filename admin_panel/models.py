from django.db import models

# Create your models here.
class Province(models.Model):
    name = models.CharField(max_length=100, verbose_name='نام استان')

class City(models.Model):
    name = models.CharField(max_length=100, verbose_name='نام شهرستان')
    province = models.ForeignKey(Province, on_delete=models.CASCADE, verbose_name='استان')

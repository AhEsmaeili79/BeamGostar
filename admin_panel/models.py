from django.db import models

# Create your models here.
class Province(models.Model):
    name = models.CharField(_('نام استان'), max_length=100)
    
    def __str__(self):
        return self.name
    
    class Meta:
        verbose_name = _('استان')
        verbose_name_plural = _('استان‌ها')

class City(models.Model):
    province = models.ForeignKey(Province, related_name='cities', on_delete=models.CASCADE)
    name = models.CharField(_('نام شهرستان'), max_length=100)
    
    def __str__(self):
        return f'{self.name} ({self.province.name})'
    
    class Meta:
        verbose_name = _('شهرستان')
        verbose_name_plural = _('شهرستان‌ها')

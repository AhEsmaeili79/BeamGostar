from django.db import models

# Menu Model (Main Menu)
class Menu(models.Model):
    name = models.CharField(max_length=100, verbose_name='نام منو')
    title = models.CharField(max_length=150, verbose_name='عنوان نمایش', blank=True)
    description = models.TextField(blank=True, verbose_name='توضیحات')
    icon = models.CharField(max_length=100, verbose_name='آیکون', blank=True)
    order = models.PositiveIntegerField(verbose_name='ترتیب نمایش', default=0)
    is_active = models.BooleanField(default=True, verbose_name='فعال')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)

    class Meta:
        ordering = ['order']
        verbose_name = 'منو'
        verbose_name_plural = 'منوها'

    def __str__(self):
        return self.title

# SubMenu Model (Holds submenu items under each Menu)
class SubMenu(models.Model):
    menu = models.ForeignKey(Menu, related_name='submenus', on_delete=models.CASCADE, verbose_name='منو')
    name = models.CharField(max_length=100, verbose_name='نام زیر منو')
    title = models.CharField(max_length=150, verbose_name='عنوان زیر منو')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)
    icon = models.CharField(max_length=100, verbose_name='آیکون', blank=True)
    order = models.PositiveIntegerField(verbose_name='ترتیب نمایش', default=0)
    is_active = models.BooleanField(default=True, verbose_name='فعال')

    class Meta:
        ordering = ['order']
        verbose_name = 'زیر منو'
        verbose_name_plural = 'زیر منوها'

    def __str__(self):
        return self.title

# SubItem Model (Items under each SubMenu)
class SubItem(models.Model):
    submenu = models.ForeignKey(SubMenu, related_name='subitems', on_delete=models.CASCADE, verbose_name='آیتم زیر منو')
    title = models.CharField(max_length=150, verbose_name='عنوان زیر منو')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)
    is_active = models.BooleanField(default=True, verbose_name='فعال')

    class Meta:
        ordering = ['submenu__order']
        verbose_name = 'آیتم زیر منو'
        verbose_name_plural = 'آیتم‌های زیر منو'

    def __str__(self):
        return self.title

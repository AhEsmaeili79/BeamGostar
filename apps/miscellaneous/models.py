from django.db import models

# Menu Model (Main Menu)
class Menu(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    name = models.CharField(max_length=100, verbose_name='نام منو')
    title = models.CharField(max_length=150, verbose_name='عنوان نمایش', blank=True)
    description = models.TextField(blank=True, verbose_name='توضیحات')
    icon = models.CharField(max_length=100, verbose_name='آیکون', blank=True)
    order = models.PositiveIntegerField(verbose_name='ترتیب نمایش', default=0)
    state  = models.BooleanField(default=True, verbose_name='فعال')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

        
    class Meta:
        ordering = ['order']
        db_table = 'menu'

    def __str__(self):
        return self.title
    
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (Menu.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)

# SubMenu Model (Holds submenu items under each Menu)
class SubMenu(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    menu = models.ForeignKey(Menu, related_name='submenus', on_delete=models.CASCADE, verbose_name='منو')
    name = models.CharField(max_length=100, verbose_name='نام زیر منو')
    title = models.CharField(max_length=150, verbose_name='عنوان زیر منو')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)
    icon = models.CharField(max_length=100, verbose_name='آیکون', blank=True)
    order = models.PositiveIntegerField(verbose_name='ترتیب نمایش', default=0)
    state = models.BooleanField(default=True, verbose_name='فعال')
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")

    
    class Meta:
        ordering = ['order']
        db_table = 'submenu'

    def __str__(self):
        return self.title
    
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (SubMenu.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)
        

# SubItem Model (Items under each SubMenu)
class SubItem(models.Model):
    id = models.IntegerField(primary_key=True, verbose_name='کد')
    submenu = models.ForeignKey(SubMenu, related_name='subitems', on_delete=models.CASCADE, verbose_name='آیتم زیر منو')
    title = models.CharField(max_length=150, verbose_name='عنوان زیر منو')
    url = models.CharField(max_length=255, verbose_name='آدرس لینک', blank=True)
    state = models.BooleanField(default=True, verbose_name='فعال')
    is_deleted = models.BooleanField(default=False, help_text="Indicates if the record is soft-deleted")


    class Meta:
        ordering = ['submenu__order']
        db_table = 'subitem'

    def __str__(self):
        return self.title
    
    def save(self, *args, **kwargs):
        if self.is_deleted:
            self.state = False
        self.id = self.id or (SubItem.objects.aggregate(models.Max('id'))['id__max'] or 0) + 1
        super().save(*args, **kwargs)
    

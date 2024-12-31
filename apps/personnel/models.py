from django.db import models

# Create your models here.
class Personnel(models.Model):
    town_id = models.IntegerField(null=True, blank=True)
    city_id = models.PositiveSmallIntegerField(default=0)
    city_id_old = models.PositiveSmallIntegerField(default=0)
    name = models.CharField(max_length=40, null=True, blank=True)
    family = models.CharField(max_length=40, null=True, blank=True)
    cooperation_id = models.PositiveSmallIntegerField()
    personnel_num = models.CharField(max_length=40, null=True, blank=True)
    national_code = models.CharField(max_length=15, null=True, blank=True)
    certificate_number = models.CharField(max_length=10, null=True, blank=True)
    father_name = models.CharField(max_length=40, null=True, blank=True)
    sex = models.SmallIntegerField(null=True, blank=True)
    registrar_id = models.IntegerField(null=True, blank=True)
    personnel_img = models.BooleanField(default=False)
    employment_kind_id = models.SmallIntegerField(null=True, blank=True)
    office_post_id = models.SmallIntegerField(null=True, blank=True)
    place_code = models.SmallIntegerField(null=True, blank=True)
    job_id = models.SmallIntegerField(null=True, blank=True)
    state = models.BooleanField(default=True)
    job_rank_id = models.IntegerField(null=True, blank=True)
    job_type_id = models.IntegerField(null=True, blank=True)
    department_id = models.IntegerField(null=True, blank=True)
    work_range = models.SmallIntegerField(null=True, blank=True)
    user_in = models.SmallIntegerField(null=True, blank=True)
    post_id = models.SmallIntegerField(null=True, blank=True)

    class Meta:
        db_table = 'personnel'

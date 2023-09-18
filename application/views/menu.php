<li class="nav-header">Персонал</li>

<?php if ( isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ) {?>
<li<?=($_SERVER['PATH_INFO'] == "/shedule/pload") ? ' class="active"' : '' ?> ><a href="<?=base_url()?>shedule/pload" title="Рабочая нагрузка на сотрудников">Работа персонала</a></li>
<?php } ?>

<li<?=($_SERVER['PATH_INFO'] == "/start") ? ' class="active"' : '' ?>><a href="<?=base_url()?>start" title="Мой рабочий график">Мой график</a></li>
<?php if(  isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ) {?>
<li class="divider"></li>
<li class="nav-header">Процессы</li>
<li<?=($_SERVER['PATH_INFO'] == "/processnew") ? ' class="active"' : '' ?>><a href="<?=base_url()?>processnew" title="Добавление нового пациента"><strong>Добавить пациента</strong></a></li>
<li<?=($_SERVER['PATH_INFO'] == "/courses") ? ' class="active"' : '' ?>><a href="<?=base_url()?>courses" title="Управление данными о ведущихся курсах">Курсы лечения</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/contracts") ? ' class="active"' : '' ?>><a href="<?=base_url()?>contracts" title="Управление данными о контрактах">Контракты</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/shedule") ? ' class="active"' : '' ?>><a href="<?=base_url()?>shedule" title="График оказываемых услуг">График услуг</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/payments") ? ' class="active"' : '' ?>><a href="<?=base_url()?>payments" title="Поступление оплаты за оказанные услуги">Оплата услуг</a></li>
<li class="divider"></li>
<li class="nav-header">Справочники</li>
<li<?=($_SERVER['PATH_INFO'] == "/refs/staff") ? ' class="active"' : '' ?>><a href="<?=base_url()?>refs/staff" title="Управление данными сотрудников">Сотрудники</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/refs/patients") ? ' class="active"' : '' ?>><a href="<?=base_url()?>refs/patients" title="Управление данными пациентов">Пациенты</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/refs/clients") ? ' class="active"' : '' ?>><a href="<?=base_url()?>refs/clients" title="Управление данными клиентов">Клиенты</a></li>
<li<?=($_SERVER['PATH_INFO'] == "/refs/services") ? ' class="active"' : '' ?>><a href="<?=base_url()?>refs/services" title="Управление данными об услугах">Услуги</a></li>

<?php if( isset($_SESSION['rank']) && $_SESSION['rank'] == 1) {?>
<li<?=($_SERVER['PATH_INFO'] == "/refs/suppliers") ? ' class="active"' : '' ?>><a href="<?=base_url()?>refs/suppliers" title="Управление данными поставшиков пациентов">Поставщики</a></li>
<?php } ?>

<li class="divider"></li>
<li class="nav-header">Редактор документов</li>
<li<?=($_SERVER['PATH_INFO'] == "/docs/editor") ? ' class="active"' : '' ?>><a href="<?=base_url()?>docs/editor" title="Простой редактор. Сохранение отключено.">Документы</a></li>
<?php } ?>
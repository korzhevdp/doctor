<? $a = $_SERVER['PATH_INFO']; ?>
<li class="nav-header">Персонал</li>
<?if($this->session->userdata('admin') == 1) {?>
<li<?=($a == "/shedule/pload") ? ' class="active"' : '' ?> ><a href="/shedule/pload" title="Рабочая нагрузка на сотрудников">Работа персонала</a></li>
<? } ?>
<li<?=($a == "/start") ? ' class="active"' : '' ?>><a href="/start" title="Мой рабочий график">Мой график</a></li>
<?if($this->session->userdata('admin') == 1) {?>
<li class="divider"></li>
<li class="nav-header">Процессы</li>
<li<?=($a == "/processnew") ? ' class="active"' : '' ?>><a href="/processnew" title="Добавление нового пациента"><strong>Добавить пациента</strong></a></li>
<li<?=($a == "/courses") ? ' class="active"' : '' ?>><a href="/courses" title="Управление данными о ведущихся курсах">Курсы лечения</a></li>
<li<?=($a == "/contracts") ? ' class="active"' : '' ?>><a href="/contracts" title="Управление данными о контрактах">Контракты</a></li>
<li<?=($a == "/shedule") ? ' class="active"' : '' ?>><a href="/shedule" title="График оказываемых услуг">График услуг</a></li>
<li<?=($a == "/payments") ? ' class="active"' : '' ?>><a href="/payments" title="Поступление оплаты за оказанные услуги">Оплата услуг</a></li>
<li class="divider"></li>
<li class="nav-header">Справочники</li>
<li<?=($a == "/refs/staff") ? ' class="active"' : '' ?>><a href="/refs/staff" title="Управление данными сотрудников">Сотрудники</a></li>
<li<?=($a == "/refs/patients") ? ' class="active"' : '' ?>><a href="/refs/patients" title="Управление данными пациентов">Пациенты</a></li>
<li<?=($a == "/refs/clients") ? ' class="active"' : '' ?>><a href="/refs/clients" title="Управление данными клиентов">Клиенты</a></li>
<li<?=($a == "/refs/services") ? ' class="active"' : '' ?>><a href="/refs/services" title="Управление данными об услугах">Услуги</a></li>
<?if($this->session->userdata('rank') == 1) {?>
<li<?=($a == "/refs/suppliers") ? ' class="active"' : '' ?>><a href="/refs/suppliers" title="Управление данными поставшиков пациентов">Поставщики</a></li>
<? } ?>
<li class="divider"></li>
<li class="nav-header">Редактор документов</li>
<li<?=($a == "/docs/editor") ? ' class="active"' : '' ?>><a href="/docs/editor" title="Простой редактор. Сохранение отключено.">Документы</a></li>
<? } ?>
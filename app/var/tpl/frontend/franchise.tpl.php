 <script src="http://yandex.st/jquery/1.8.3/jquery.min.js"></script>
	<style>
		.mleft {
		  float: left;
		  width: 48%;
		}
		.mright {
		  float: right;
		  width: 48%;		  
		}
		.b1 {
			background:    #f00;
			background:    -webkit-linear-gradient(#f00, #900);
			background:    linear-gradient(#f00, #900);
			border-radius: 5px;
			color:         #fff;
			display:       inline-block;
			padding:       8px 20px;
			font:          normal 700 22px/1 "Calibri", sans-serif;
			text-align:    center;
			text-shadow:   1px 1px 0 #000;
			cursor:pointer;

		}
		
		
		.header-knopki-ssil2 {
			list-style:none;
			padding:0;
			overflow:hidden;
			position:relative;
			z-index:1;
			margin: 0 auto;
		}
		
		
		.header-knopki-ssil2 li {
			float: left;

			position: relative;
			z-index:  1;

			text-align: center;

			border-top:    1px solid #f5a9ac;
			border-bottom: 1px solid #e73e43;

			background-color: #e31e24;
			background-image: -moz-linear-gradient(top, #ee777a, #e31e24);
			background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee777a), to(#e31e24));
			background-image: -webkit-linear-gradient(top, #ee777a, #e31e24);
			background-image: -o-linear-gradient(top, #ee777a, #e31e24);
			background-image: linear-gradient(to bottom, #ee777a, #e31e24);
			background-repeat: repeat-x;
			filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEE777A', endColorstr='#FFE31E24', GradientType=0)";

			-webkit-border-radius: 6px;
			border-radius:         6px;

			line-height: 36px;

			width:  202px;
			height: 36px;
			margin: 0 8px 0 0;
		}

		.header-knopki-ssil2 li.active-wrap {
			background: #ca171d;

			border-top-color:    #f01e25;
			border-bottom-color: #f01e25;
		}

		.header-knopki-ssil2 li .icon-left,
		.header-knopki-ssil2 li .icon-right {
			background: transparent url(../../img/frontend/icon-header-menu.png) 0 0 no-repeat;

			display: block;
			width:   24px;
			height:  18px;

			position: absolute;

			top: 9px;
		}

		.header-knopki-ssil2 li .icon-left {
			left: 8px;
		}

		.header-knopki-ssil2 li .icon-right {
			right: 8px;

			background-position: 0 -36px;
		}

		.header-knopki-ssil2 li.last {
			margin-right: 0;
		}

		.header-knopki-ssil2 li a {
			text-decoration: none;
			color: #fefefe;
			font-size: 14px;
			font-weight: 700;
		}

		.header-knopki-ssil2 li a:hover {
			text-decoration: underline;
		}

	</style>
	
	
	<div style="width: 100%">
		  <div class="mleft">
			<span><b><h4>Франчайзинг от «МОЙ ГОРЯЩИЙ ТУР» это:</h4></b></span>
			<div class="b-banner-opl-body b-adres">
				<ul>
					<li>1.	 Возможность работать под известным брендом;</li><br />
					<li>2.	 Готовая бизнес-модель, проверенная годами на
						собственных офисах продаж;</li><br />
					<li>3.	 Повышение прибыльности бизнеса за счет 
						увеличения туристического потока;</li><br />
					<li>4.	 Срок запуска от 14 дней;</li><br />					
					<li>5.	 Окупаемость от 2 месяцев.</li><br />
				</ul>
				
				<ul class="header-knopki-ssil2">
					<li class=" first">
                                
                        <a href="http://www.moihottur.ru/static/pdf/press.pdf" class="first-level" title="Посмотреть презентацию">Посмотреть презентацию</a>   
                    </li>
				</ul>
			</div>
		  </div>
		  
		  <div class="mright">
		  
			<span><b><h4>Заявка на вступление в сеть:</h4></b></span>
			<div class="b-banner-opl-body b-adres">
			
				<form method="post" action="" id="ajaxform1"> 
				
					<div class="control-group">
						<label for="name">Ваше имя <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="name" name="name" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="city">Город <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="city" name="city" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="phone">Телефон <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="phone" name="phone" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="email">E-mail <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="email" class="input-text" id="email" name="email" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="agency">Агентство <span class="form-asterisk">*</span></label>
						<br><br>
						<div >
							<input type="radio" name="agency" value="Новое" checked>Новое
							<input type="radio" name="agency" value="Дейтсвующее">Дейтсвующее
						</div>
					</div>
					<br><br>
					 
					
					 
					 <input type="submit" class="b1" value="Подать заявку"></p>
	
				</form>
			</div>
		  </div>
	</div>
	
	
	<div>
		<img src="http://www.moihottur.ru/static/img/frontend/frans/graf.png" width="100%">
	</div>
	<hr>
	<div>
		<img src="http://www.moihottur.ru/static/img/frontend/frans/icons.png" width="100%">
	</div>
	<hr>
	<div class="b-banner-opl-body b-adres">
	
		<span><h2>От Вас требуется:</h2></span>
			<ul>
			<li><h3>1. Арендованное или собственное помещение с доступом в интернет</h3></li>
			<li><h3>2. Желание и готовность развиваться в туристическом бизнесе</h3></li>
			<li><h3>3. Начальные инвестиции от 75 тыс. руб. на паушальный взнос, 
			аренду и ремонт офиса, печать фирменной продукции.</h3></li>
			</ul>
	</div>
	<hr>
	<div>
		<img src="http://www.moihottur.ru/static/img/frontend/frans/nagr.png" width="100%">
	</div>
	<hr>
	
	<div >
		  <div class="mleft">
			<span><b><h4>Франчайзинг от «МОЙ ГОРЯЩИЙ ТУР» это:</h4></b></span>
			<div class="b-banner-opl-body b-adres">
				<ul>
						<li>1.	 Возможность работать под известным брендом;</li><br />
						<li>2.	 Готовая бизнес-модель, проверенная годами на
							собственных офисах продаж;</li><br />
						<li>3.	 Повышение прибыльности бизнеса за счет 
							увеличения туристического потока;</li><br />
						<li>4.	 Срок запуска от 14 дней;</li><br />
						
						<li>5.	 Окупаемость от 2 месяцев.</li><br />
				</ul>
				
				<ul class="header-knopki-ssil2">
					<li class=" first">
                                
                        <a href="static/pdf/press.pdf" class="first-level" title="Посмотреть презентацию">Посмотреть презентацию</a>   
                    </li>
				</ul>
			</div>
		  </div>
		  <div class="hr"></div>
		 <div class="mright">
		  
			<span><b><h4>Заявка на вступление в сеть:</h4></b></span>
			<div class="b-banner-opl-body b-adres">
			
				<form method="post" id="ajaxform2"> 
				
					<div class="control-group">
						<label for="name">Ваше имя <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="name" name="name" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="city">Город <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="city" name="city" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="phone">Телефон <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="text" class="input-text" id="phone" name="phone" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="email">E-mail <span class="form-asterisk">*</span></label>

						<div class="controls">
							<input type="email" class="input-text" id="email" name="email" value="" required="required">
						</div>
					</div>
					<br>
					<div class="control-group">
						<label for="agency">Агентство <span class="form-asterisk">*</span></label>
						<br><br>
						<div >
							<input type="radio" name="agency" value="Новое" checked>Новое
							<input type="radio" name="agency" value="Дейтсвующее">Дейтсвующее
						</div>
					</div>
					<br><br>
					 
					 <input type="submit" class="b1" title="Подать заявку" value="Подать заявку"></p>
	
				</form>
			</div>
		  </div>
	</div>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#ajaxform1").submit(function(event) {
			event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
            type: "POST", 
            url: "http://www.moihottur.ru/helper/send.php", 
            data: form_data,
            success: function() {
                   alert("Спасибо за обращение, представитель отдела франчайзинга перезвонит вам втечение 2 рабочих дней!");
            }
		});
	}); 

	$("#ajaxform2").submit(function(event) {
			event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
            type: "POST", 
            url: "http://www.moihottur.ru/helper/send.php", 
            data: form_data,
            success: function() {
                   alert("Спасибо за обращение, представитель отдела франчайзинга перезвонит вам втечение 2 рабочих дней!");
            }
		});
	}); 
}); 
</script>
	
	
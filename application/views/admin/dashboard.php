<section id="dashboardAdmin" class="admin">
	<?php if (isset($model_check[0])) { ?>
	<div class="well">
		<ul class="list-unstyled mb0">
			<?php foreach ($model_check as $value) { ?>
			<li>
				<p>
					<?php
					switch ($this->config->item('language')) {
						case 'korean' :
								?>
					<?php echo ucfirst($value); ?> 모듈의 업데이트가 존재합니다. 
								<?php
							break;
						case 'japanese' :
								?>
					<?php echo ucfirst($value); ?>モジュールのアップデートがあります。
								<?php
							break;
						default :
								?>
					New update model for <?php echo ucfirst($value); ?>.
								<?php
							break;
					}
					?>
				</p>
				<div class="text-right">
					<a class="btn btn-primary" target="hIframe" href="<?php echo base_url('/admin/install/'.$value.'/'); ?>"><?php echo lang('text_submit'); ?></a>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</section>
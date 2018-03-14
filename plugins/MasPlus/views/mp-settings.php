<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo T($this->Data['PluginDescription']);?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li>
   <?php      
     echo $this->Form->Label('その他追加テキスト', 'Plugin.MasPlus.Text');
     echo $this->Form->TextBox('Plugin.MasPlus.Url1');
     ?></li>
    <li>
   <?php      
     echo $this->Form->Label('/php-mt/の設置場所(相対パス)', 'Plugin.MasPlus.Text');
     echo $this->Form->TextBox('Plugin.MasPlus.Location');
     ?>/php-mt/</li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>
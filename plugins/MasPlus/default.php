<?php if (!defined('APPLICATION')) exit();
$PluginInfo['MasPlus'] = array(
   'Description' => "被災地支援のためのマストドン研究会用に製作",
   'Version' => '1.0',
   'MobileFriendly'=>TRUE,
   'SettingsUrl' => '/plugin/MasPlus',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => "Cutls P",
   'AuthorEmail' => 'web-pro@cutls.com',
   'AuthorUrl' => "https://donken.org/forum/index.php?p=/",
   'License'=>"GNU GPL2"
);
class MasPlusPlugin extends Gdn_Plugin {
  public function __construct() {}
   public function Base_Render_Before($Sender) {
$Url1 = C('Plugin.MasPlus.Text');
$loc = C('Plugin.MasPlus.Location');

$MasPlusJQuerySource =
'<script type="text/javascript">
jQuery(document).ready(function($){
$(\'.PanelColumn\').append(\'<h4>マストドン</h4>\');
$(\'.PanelColumn\').append(\'<iframe style="width:400px; max-width:100%; height:500px;" frameborder="0" src="'.$loc.'/php-mt/show.php"></iframe>\');
$(\'.PanelColumn\').append(\'<br><a onclick="share()" class="Button Primary">このページをトゥート</a><br>\');
$(\'.PanelColumn\').append(\'<br><a href="./mastodon-login/setting.php" class="Button Primary">マストドン連携</button>\');
$(\'.PanelColumn\').append(\''.$Url1.'\');
});
function share(){
  var link=\'./mastodon-login/share?text=\'+document.title+\'+\'+window.location.href;
  window.open(link, "_blank");
   }
</script>';
$Sender->Head->AddString($MasPlusJQuerySource);
}
   public function PluginController_Index_Create($Sender) {
      $Sender->Title('MasPlus Plugin');
      $Sender->AddSideMenu('plugin/MasPlus');
      $Sender->Form = new Gdn_Form();
      $this->Dispatch($Sender, $Sender->RequestArgs);
     }
   public function Controller_MasPlus($Sender) {
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));
    $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
         'Plugin.MasPlus.Text' => '',
         'Plugin.MasPlus.Location'=>'..'
      ));
        $Sender->Form->SetModel($ConfigurationModel);
        if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
            $Sender->Form->SetData($ConfigurationModel->Data);
        } else {
            $Data = $Sender->Form->FormValues();
            if ($Sender->Form->Save() !== FALSE)
                $Sender->StatusMessage = T("Your settings have been saved.");
        }
      $Sender->Render($this->GetView('mp-settings.php'));
   }

  public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Add-ons', 'MasPlus', 'plugin/MasPlus', 'Garden.Settings.Manage');
   }
   //Add cleditor and ButtonBar editors to the form if enabled
   public function PluginController_Render_Before($Sender){ 
    $Sender->AddCssFile('plugins/Index/design/customadmin.css');
}
	
   public function Setup() {
      SaveToConfig('Plugin.MasPlus.Text','');
      SaveToConfig('Plugin.MasPlus.Location','..');
   }
     public function OnDisable() {
      return TRUE;
   }

}


	
        	   

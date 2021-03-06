<?php

namespace NV2;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\level\particle\{AngryVillagerParticle,BubbleParticle,CriticalParticle,DestroyBlockParticle,DustParticle,EnchantmentTableParticle,EnchantParticle,EntityFlameParticle,LargeExplodeParticle,FlameParticle,HappyVillagerParticle,HeartParticle,InkParticle,InstantEnchantParticle,ItemBreakParticle,LavaDripParticle,LavaParticle,MobSpellParticle,PortalParticle,RainSplashParticle,RedstoneParticle,SmokeParticle,SpellParticle,SplashParticle,SporeParticle,TerrainParticle,WaterDripParticle,WaterParticle,WhiteSmokeParticle};
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Inventory;
use pocketmine\level\Level;
use pocketmine\entity\human;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;
use slapper\entities\SlapperHuman;
use slapper\events\SlapperHitEvent;

class Main extends PluginBase implements Listener{

public $data;
private $config, $amount;
public $money;

public function onEnable(){
if(!file_exists($this->getDataFolder() . "DiemMy")){
@mkdir($this->getDataFolder() . "DiemMy");
}
$this->quest = new Config($this->getDataFolder() . "DiemMy" . "diemy.yml", Config::YAML);
$this->saveDefaultConfig();
$this->config = $this->getConfig();
$this->config->save();
$this->money = EconomyAPI::getInstance();
$this->kakashi = $this->getServer()->getPluginManager()->getPlugin("NV_Mitsuhi_Otake");
$this->nv = $this->getServer()->getPluginManager()->getPlugin("NGVS_Quest");
$this->getServer()->getPluginManager()->registerEvents($this,$this);
}
public function onJoin(PlayerJoinEvent $event){
if(!($this->quest->exists(strtolower($event->getPlayer()->getName())))){
$this->quest->set(strtolower($event->getPlayer()->getName()), ["1" => "Not", "2" => "Not", "3" => "Not", "4" => "Not", "5" => "Not", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
return true;
}
}
public function get1(Player $player){
$get1 = $this->quest->get(strtolower($player->getName()))["1"];
return $get1;
}
public function get2(Player $player){
$get2 = $this->quest->get(strtolower($player->getName()))["2"];
return $get2;
}
public function get3(Player $player){
$get3 = $this->quest->get(strtolower($player->getName()))["3"];
return $get3;
}
public function get4(Player $player){
$get4 = $this->quest->get(strtolower($player->getName()))["4"];
return $get4;
}
public function get5(Player $player){
$get5 = $this->quest->get(strtolower($player->getName()))["5"];
return $get5;
}
public function get6(Player $player){
$get6 = $this->quest->get(strtolower($player->getName()))["6"];
return $get6;
}
public function get7(Player $player){
$get7 = $this->quest->get(strtolower($player->getName()))["7"];
return $get7;
}
public function get8(Player $player){
$get8 = $this->quest->get(strtolower($player->getName()))["8"];
return $get8;
}
public function setDone1(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Not", "3" => "Not", "4" => "Not", "5" => "Not", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone2(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Not", "4" => "Not", "5" => "Not", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone3(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Not", "5" => "Not", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone4(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Done", "5" => "Not", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone5(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Done", "5" => "Done", "6" => "Not", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone6(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Done", "5" => "Done", "6" => "Done", "7" => "Not", "8" => "Not"]);
$this->quest->save();
}
public function setDone7(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Done", "5" => "Done", "6" => "Done", "7" => "Done", "8" => "Not"]);
$this->quest->save();
}
public function setDone8(Player $player){
$this->quest->set(strtolower($player->getName()), ["1" => "Done", "2" => "Done", "3" => "Done", "4" => "Done", "5" => "Done", "6" => "Done", "7" => "Done", "8" => "Done"]);
$this->quest->save();
}
public function onTap(SlapperHitEvent $ev){
$entity = $ev->getEntity();
$damager = $ev->getDamager();
if(!$entity instanceof SlapperHuman && $entity->getName() !== "??l??e?????c!??e?????r??f Shigeo Toku"){
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get1($damager) == "Not"){
$this->UIquest1($damager);
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get1($damager) == "Done" && $this->get2($damager) == "Not"){
$this->UIquest2($damager);
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get2($damager) == "Done" && $this->get3($damager) == "Not"){
$this->UIquest3($damager);
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get3($damager) == "Done" && $this->get4($damager) == "Not"){
$this->UIquest4($damager, "??f????u l?? m??u ch??? SkyBlock hay nh???t?");
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get4($damager) == "Done" && $this->get5($damager) == "Not"){
$this->UIquest5($damager, "??fSkyBlock Co-Op\n??7C?? Ph???i L?? Th??? Lo???i Pay To Win Hay Kh??ng? Hay C??y Chay V???n M???nh?");
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??fShigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get5($damager) == "Done" && $this->get6($damager) == "Not"){
$this->UIquest6($damager, "??f ????u L?? M??y Ch??? ????ng Member Nh???t Unikfamily?");
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get6($damager) == "Done" && $this->get7($damager) == "Not"){
$this->UIquest7($damager, "??f????u L?? T??n c???a server T???ng?");
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get7($damager) == "Done" && $this->get8($damager) == "Not"){
$this->UIquest8($damager);
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Done" && $this->get7($damager) == "Done" && $this->get8($damager) == "Done"){
$this->UIthongbao($damager, "??f?????a ???? ho??n th??nh nhi???m v??? c???a NPC n??y!");
return true;
}
if($entity instanceof SlapperHuman && $entity->getName() == "??l??e?????c!??e?????r??f Shigeo Toku" && $this->kakashi->get2($damager) == "Not" && $this->get1($damager) == "Not"){
$this->UIthongbao($damager, "??f?????a Vui l??ng ho??n th??nh nhi???m v??? theo s??? th??? t???!");
return true;
}

}
public function UIthongbao($player, $message){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null) use ($message){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent($message);
$form->addButton("??l??1???? Hi???u");
$form->sendToPlayer($player);
return $form;
}
public function UIquest1($player){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest2($player);
$this->setDone1($player);
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??fXin Ch??o ".$player->getName().", B???n ???? s???n s??ng cho th??? th??ch ch??a?");
$form->addButton("??l??1R???i");
$form->sendToPlayer($player);
return $form;
}
public function UIquest2($player){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest3($player);
$this->setDone2($player);
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??f N???u b???n v?????t qua c??c c??u h???i sau b???n s??? ???????c c???p ph??p nhi???m v??? m???i");
$form->addButton("??l??1Ti???p T???c");
$form->sendToPlayer($player);
return $form;
}
public function UIquest3($player){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest4($player, "??fShigeo Toku");
$this->setDone3($player);
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??fB???t ?????u th??? th??ch lu??n nh??!");
$form->addButton("??l??1Th???c Hi???n Th??? Th??ch");
$form->sendToPlayer($player);
return $form;
}
public function UIquest4($player, $msg){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest5($player, "??bSkyBlock Co-Op");
$this->setDone4($player);
break;
case 1:
$this->UIquest4($player, "??3SkyBlock");
break;
case 2:
$this->UIquest4($player, "??cSkyBlock");
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??r??f ????u L?? M??y Ch??? SkyBlock Hay Nh???t!");
$form->addButton("??l??eSkyBlock ??bCo-Op");
$form->addButton("??l??3SkyBlock");
$form->addButton("??l??cSurvival");
$form->sendToPlayer($player);
return $form;
}
public function UIquest5($player, $msg){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest6($player, "??f????u L?? T??n C???a Server T???ng?");
$this->setDone5($player);
break;
case 1:
$this->UIquest5($player, "??fSkyBlock Co-Op");
break;
case 2:
$this->UIquest5($player, "??fSkyBlock Co-Op");
break;
case 3:
$this->UIquest5($player, "??fSkyBlock Co-Op");
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent($msg);
$form->addButton("??l??eUnikFamily");
$form->addButton("??l??cVictory");
$form->addButton("??l??3SkyBlock");
$form->addButton("??l??eSkyBlock ??bCo-Op");
$form->sendToPlayer($player);
return $form;
}
public function UIquest6($player, $msg){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest6($player, "??f????u L?? T??n C???a Server T???ng?");
break;
case 1:
$this->UIquest7($player, "??fSkyBlock Co-Op\n??7C?? Ph???i L?? Th??? Lo???i: ??ua Top Kh??ng Pay To Win, C??y Chay V???n M???nh?");
$this->setDone6($player);
break;
case 2:
$this->UIquest6($player, "??f????u L?? T??n C???a Server T???ng?");
break;
case 3:
$this->UIquest6($player, "??f????u L?? T??n C???a Server T???ng?");
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??fTh??? Lo???i ???????c D??ng Trong SkyBlock Co-Op L?? Th??? Lo???i G???");
$form->addButton("??l??cPay To Win");
$form->addButton("??l??c??u Ti??n Member");
$form->addButton("??l??cGi???ng SkyFree");
$form->addButton("??l??cB??? L?????t");
$form->sendToPlayer($player);
return $form;
}
public function UIquest7($player, $msg){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->UIquest8($player);
$this->setDone7($player);
break;
case 1:
$this->UIquest7($player, "??f????u L?? M??y Ch??? ????ng Member nh???t Unikfamily");
$this->setDone6($player);
break;
case 2:
$this->UIquest7($player, "??f??fTh??? Lo???i ???????c D??ng Trong SkyBlock Co-Op L?? Th??? Lo???i G???");
break;
case 3:
$this->UIquest7($player, "??fTh??? Lo???i ???????c D??ng Trong SkyBlock Co-Op L?? Th??? Lo???i G???");
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??f????u L?? M??y Ch??? ????ng Member nh???t Unikfamily");
$form->addButton("??l??1SkyBlock");
$form->addButton("??l??1Survival");
$form->addButton("??l??1SkyBlock Co-Op");
$form->addButton("??l??1RPG");
$form->sendToPlayer($player);
return $form;
}
public function UIquest8($player){
$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
$form = $api->createSimpleForm(function (Player $player, int $data = null){
$result = $data;
if($result === null){
return true;
}
switch($result){
case 0:
$this->setDone8($player);
$player->addTitle("??l??a?????6?????a?????r??c???? Ho??n Th??nh Xong Th??? Th??ch");
$player->sendMessage("??r??7Quay L???i n??i chuy???n v???i Otake");
$this->money->addMoney($player->getName(), 1000);
$dc = Item::get(Item::EMERALD, 0, 32);
$player->getInventory()->addItem($dc);
$dia = Item::get(Item::DIAMOND, 0, 32);
$player->getInventory()->addItem($dia);
$breas = Item::get(Item::BREAD, 0, 16);
$player->getInventory()->addItem($breas);
$this->nv->setNV($player, "Gi??p ?????", "Mitsuhi Otake", "N??i Chuy???n");
break;
}
});
$form->setTitle("??l??e?????c!??e?????r??fShigeo Toku");
$form->setContent("??fKh??ng h??? danh l?? Ng?????i ch??i su???t s???c c???a M??y ch???! ???????c r???i, ta s??? cho ng?????i 1 ??t ????? ????? d??? ph??ng. H??y t??m ?????n NPC nhi???m v??? ti???p theo ????? l??m nh??!\n??ePh???n Th?????ng:\n??f?????c 1.000??a Xu\n??f?????c 32 Emerald\n??f?????c 32 Diamond\n??f?????c 16 B??nh M??\n\n");
$form->addButton("??l??6HO??N TH??NH");
$form->sendToPlayer($player);
return $form;
}

/*public function onFight(EntityDamageEvent $event) {
if($event instanceof EntityDamageByEntityEvent) {
$hit = $event->getEntity();
$damager = $event->getDamager();
if($damager instanceof Player) {
if($hit instanceof SlapperHuman && !$event->isCancelled()){
$event->setCancelled(true);
}
}
}
}*/

}
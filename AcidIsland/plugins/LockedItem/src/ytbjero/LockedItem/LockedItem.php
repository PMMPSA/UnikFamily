<?php

declare (strict_types=1);

namespace ytbjero\LockedItem;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\EnderChestInventory;
use pocketmine\inventory\PlayerInventory;
use UltraFlappy\ShulkerBox\Inventory\ShulkerBoxInventory;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\PluginBase;

use pocketmine\block\Block;
use pocketmine\item\Item;

use pocketmine\nbt\tag\StringTag;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use ytbjero\LockedItem\libs\JackMD\UpdateNotifier\UpdateNotifier;
class LockedItem extends PluginBase implements Listener{
    
    private static $instance;
    
    const KEY_VALUE = "isLocked";

    public function onLoad() : void 
    {
        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
    }

    public function onEnable() : void
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
   
    public static function getInstance() : self
    {
        return self::$instance;
    }

    public function onDrop(PlayerDropItemEvent $event) : void
    {
        $item = $event->getItem();
        if ($this->isLocked($item)) {
                $event->setCancelled(true);
        }
    }

    public function onTouch(PlayerInteractEvent $event) : void
    {
            $player = $event->getPlayer();
            $item = $player->getInventory()->getItemInHand();
            $block = $event->getBlock();
            if($block->getId() === Block::ITEM_FRAME_BLOCK){
                if ($this->getConfig()->get("no-touch") == true) {
                    if ($this->isLocked($item)) {
                        $event->setCancelled(true);
                        }
                    }
                }
            }
     /**
      * @param InventoryTransactionEvent $event
      * @return void
      */
    public function onTitle(InventoryTransactionEvent $event) : void
    {
        $iPlayer = null;
        $iChest = null;
        foreach ($event->getTransaction()->getActions() as $action){
            foreach ($event->getTransaction()->getInventories() as $inventory){
                if($inventory instanceof PlayerInventory){
                    $iPlayer = true;
                }
                if($inventory instanceof ChestInventory || $inventory instanceof DoubleChestInventory || $inventory instanceof EnderChestInventory || $inventory instanceof ShulkerBoxInventory){
                    $iChest = true;
                }
                if($iPlayer && $iChest){
                    $player = $inventory->getViewers()[key($inventory->getViewers())];
                    $item = $action->getTargetItem();
                    if($this->getConfig()->get("no-change-inventory") == true){
                        if($this->isLocked($item)){
                            $event->setCancelled(true);
                        }
                    }
                }
            }
        }
    }
    
    public function onCommand(CommandSender $sender, Command $command, String $label, Array $args) : bool
    {
        if($command->getName() == "setlock") {
            if(!$sender instanceof Player) {
                $sender->sendMessage(TF::RED . "This command only works in game!");
                return false;
            }
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() == 0){
                $sender->sendMessage($this->getConfig()->get("item-hand-message"));
                return false;
            }
            $item = $sender->getInventory()->getItemInHand();
                        if(!$this->isLocked($item)) {
                            $sender->getInventory()->setItemInHand($this->setLocked($item));
                            $sender->sendMessage($this->getConfig()->get("lock-message"));
                        } else{
                        $sender->sendMessage($this->getConfig()->get("no-lock-message"));
                        }
                    }
        if($command->getName() == "unlock") {
            if(!$sender instanceof Player) {
                $sender->sendMessage(TF::RED . "This command only works in game!");
                return false;
            }
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() == 0){
                $sender->sendMessage($this->getConfig()->get("item-hand-message"));
                return false;
            }
            $item = $sender->getInventory()->getItemInHand();
                        if($this->isLocked($item)) {
                           $sender->getInventory()->setItemInHand($this->unLock($item));
                            $sender->sendMessage($this->getConfig()->get("unlock-message"));
                        }else{
                       $sender->sendMessage($this->getConfig()->get("no-unlock-message"));
                }
            }
                return true;
        }

    
    public function isLocked(Item $item): bool
    {
        return $item->getNamedTag()->hasTag("Status", StringTag::class);
    }

    public function setLocked(Item $item) : Item
    {
        if(!$this->isLocked($item)) {
           $item->setNamedTagEntry(new StringTag("Status", self::KEY_VALUE));
           $status = $item->getLore();
           $status[] = $this->getConfig()->get("item-lore");
           $item->setLore($status);   
        }   
        return $item;
    }
    
    public function unLock(Item $item): Item
    {
        if($this->isLocked($item)) {
           $item->getNamedTag()->removeTag("Status");
           $status = $item->getLore();
         unset($status[array_search($this->getConfig()->get("item-lore"), $status)]);
         $item->setLore($status);
        }
        return $item;
    }
}

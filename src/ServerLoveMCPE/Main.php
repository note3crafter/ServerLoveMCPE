<?php
namespace ServerLoveMCPE;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->nolove = new Config($this->getDataFolder()."nolove.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::RED . " Yayyy, ServerLoveMCPE is ready for love on Version ".$this->getDescription()->getVersion());
    }
    
    public function onDisable(){
        $this->nolove->save();
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
/**
*
*                                         LOVE
*
**/
            case "love":
                if(!(isset($args[0]))){
                    return false;
                }
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage("§5 Sorry, " . $loved . "§5 is not looking to love anyone right now.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage($sender->getName()."§5 is in love with you!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                        }
                        $sender->sendMessage("§5So you love §a" . $loved . "?§5 Awww, thats nice");
                        $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dis in love with §a" . $loved . "§d.");
                        return true;
                    }else{
                        $sender->sendMessage($loved . "§5 is not avalible for love. #shameful. §5 Basically, §a" . $loved . "§5 does not exist, or is not online.");
                        return true;
                    }
                }
/**
*
*                                 BREAKUP
*
**/
                break;
            case "breakup":
                if(!(isset($args[0]))){
                    return false;
                }
                $loved = array_shift($args);
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage($sender->getName()."§5 has broken up with you!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                        }
                        $sender->sendMessage("§5You have broken up with §a" . $loved . "§5.");
                        $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dhas broken up with §a" . $loved . "§d.");
                        return true;
                    }else{
                        $sender->sendMessage($loved . "§5 is not avalible for a breakup. Basically, §a" . $loved . "§5 does not exist, or is not online.");
                        return true;
                    }
/**
*
*                                      NOLOVE
*
**/
            break;
            case "nolove":
                if(!(isset($args[0]))){
                    return false;
                }
                if($args[0] == "nolove"){
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage("§5You will no longer be loved. §e#ForEverAlone");
                    return true;
                }elseif($args[0] == "love"){
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage("§5You will now be loved again! §e#GetInThere");
                    return true;
                }else{
                    return false;
                }
/**
*
*                                   SERVERLOVE
*
**/
            break;
            case "serverlove":
                $sender->sendMessage("§5[ServerLoveMCPE] Original ServerLove (For MCPC )  Made By ratchetgame98 ");
                $sender->sendMessage("§d[ServerLoveMCPE] Usage: /love <playerName>");
                $sender->sendMessage("§d[ServerLoveMCPE] Usage: /breakup <playerName>");
                $sender->sendMessage("§5[ServerLoveMCPE] Usage: /nolove <nolove / love> ");
                $sender->sendMessage("§d[ServerLoveMCPE] Happy Loving!");
                return true;
            break;
        default:
            return false;
        }
    return false;
    }
}

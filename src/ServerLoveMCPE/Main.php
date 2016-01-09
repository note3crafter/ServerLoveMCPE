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
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] Yayyy, ServerLoveMCPE is ready for love on Version ".$this->getDescription()->getVersion());
    }
    
    public function onDisable(){
        $this->nolove->save();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] You've broken up with the server.");
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
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage("§5[♥]Sorry, " . $loved . "§5 is not looking to love anyone right now.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        if($lovedPlayer == $sender){
                            //This is where the loop for the #ForeverAlone goes to - by ratchetgame98
                            //You can personlise the messages to your liking also
                            $sender->sendMessage("§5[♥]You can't love yourself :P");
                            $this->getServer()->broadcastMessage($sender->getName() . "§5[♥] §etried to love themselves :P. §6#ForeverAlone");
                        }else{
                            $lovedPlayer->sendMessage($sender->getName()."§5 is in love with you!");
                            /** $sender->setNameTag($sender->getName() . "- ♥");
                            * WON'T WORK ATM
                            $loved->setNameTag($loved->getName() . "- ♥"); **/
                            if(isset($args[0])){
                                $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                            }
                            $sender->sendMessage("§5[♥] So you love §a" . $loved . "?§5 Awww, thats nice");
                            $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dis in love with §a" . $loved . "§d.");
                            $this->getServer()->broadcastMessage("§d♥" . $loved . "§d♥" . $sender->getName() . "§d♥");
                            return true;
                        }
                    }else{
                        $sender->sendMessage("§5[♥] §a" . $loved . "§5 is not avalible for love. #shameful. §5 Basically, §a" . $loved . "§5 does not exist, or is not online.");
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
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                $loved = array_shift($args);
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage("§5[♥]§a" . $sender->getName() ."§5has broken up with you!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                        }
                        $sender->sendMessage("§5[♥] You have broken up with §a" . $loved . "§5.");
                        $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dhas broken up with §a" . $loved . "§d.");
                       /** $sender->setNameTag($sender->getName() . "");
                        * WON'T WORK ATM
                        $loved->setNameTag($loved->getName() . ""); **/
                        return true;
                    }else{
                        $sender->sendMessage($loved . "§5[♥] is not avalible for a breakup. Basically, §a" . $loved . "§5 does not exist, or is not online.");
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
                if (!($sender instanceof Player)){ 
                $sender->sendMessage("§5[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                if($args[0] == "nolove"){
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage("§5[♥] You will no longer be loved. §e#ForEverAlone");
                    return true;
                }elseif($args[0] == "love"){
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage("§5[♥] You will now be loved again! §e#GetInThere");
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
                $sender->sendMessage("§5[♥][ServerLoveMCPE] Original ServerLove (For MCPC )  Made By ratchetgame98 ");
                $sender->sendMessage("§d[♥][ServerLoveMCPE] Usage: /love <playerName>");
                $sender->sendMessage("§d[♥][ServerLoveMCPE] Usage: /breakup <playerName>");
                $sender->sendMessage("§d[♥][ServerLoveMCPE] Usage: /nolove <nolove / love> ");
                $sender->sendMessage("§5[♥][ServerLoveMCPE] Happy Loving!");
                return true;
            break;
        default:
            return false;
        }
    return false;
    }
}

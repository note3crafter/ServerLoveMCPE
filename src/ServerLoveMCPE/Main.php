<?php
namespace ServerLoveMCPE;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{
    public function onLoad()
    {
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE is loading.");
    }
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->nolove = new Config($this->getDataFolder() . "nolove.yml", Config::YAML);
        $this->love   = new Config($this->getDataFolder() . "love.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[<3] Yayyy, ServerLoveMCPE is ready for love on Version " . $this->getDescription()->getVersion());
    }
    
    public function onDisable()
    {
        $this->nolove->save();
        $this->love->save();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[<3] You've broken up with the server.");
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $sender = $event->getPlayer();
        $player = $event->getPlayer()->getName();
        if ($this->love->exists($player)) {
            $sender->setDisplayName(TextFormat::LIGHT_PURPLE . "[<3]" . $sender->getDisplayName());
            
        } else {
            $sender->setDisplayName($sender->getDisplayName());
        }
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        switch ($command->getName()) {
            /**
             *
             *                                         LOVE
             *
             **/
            case "love":
                if (!(isset($args[0]))) {
                    return false;
                }
                if (!($sender instanceof Player)) {
                    $sender->sendMessage("§5[<3] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                $p = $sender->getName();
                if ($this->love->exists($p)) {
                    $sender->sendMessage("§5[<3] You already have a boyfriend or girlfriend!");
                }
                
                $loved = array_shift($args);
                if ($this->nolove->exists(strtolower($loved))) {
                    $sender->sendMessage("§5[<3]Sorry, " . $loved . "§5 is not looking to love anyone right now.");
                    return true;
                } else {
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if ($lovedPlayer !== null and $lovedPlayer->isOnline()) {
                        if ($lovedPlayer == $sender) {
                            /*This is where the loop for the #ForeverAlone goes to - by ratchetgame98 - Original ServerLove ( MCPC ) owner!*/
                            $sender->sendMessage("§5[<3]You can't love yourself :P");
                            $this->getServer()->broadcastMessage("§5[<3]§a" . $sender->getName() . "§5 §etried to love themselves :P. §6#ForeverAlone");
                            
                            
                        } else {
                            $lovedPlayer->sendMessage("§5[<3]§a" . $sender->getName() . "§5is in love with you!");
                            if (isset($args[0])) {
                                $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                            }
                            $sender->sendMessage("§5[<3] So you love §a" . $loved . "?§5 Awww, thats nice");
                            $this->getServer()->broadcastMessage("§a" . $sender->getName() . " §dis in love with §a" . $loved . "§d.");
                            $this->getServer()->broadcastMessage("§d♥" . $loved . "§d ♥ " . $sender->getName() . "§d♥");
                            $lovedPlayer->getLevel()->addParticle(new \pocketmine\level\particle\HeartParticle($lovedPlayer));
                            $sender->getLevel()->addParticle(new \pocketmine\level\particle\HeartParticle($sender));
                            
                            $sender1      = $sender->getName();
                            $lovedPlayer1 = $lovedPlayer->getName();
                            $this->love->set($sender1);
                            $this->love->set($lovedPlayer1);
                            $this->love->save();
                            
                            
                            /*nametag thing */
                            $sender->setDisplayName(TextFormat::LIGHT_PURPLE . "[<3]" . $sender->getDisplayName());
                            $lovedPlayer->setDisplayName(TextFormat::LIGHT_PURPLE . "[<3]" . $lovedPlayer->getDisplayName());
                            /*nametag thing */
                            
                            return true;
                        }
                    } else {
                        $sender->sendMessage("§5[<3] §a" . $loved . "§5 is not avalible for love. #shameful. §5 Basically, §a" . $loved . "§5 does not exist, or is not online.");
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
                if (!(isset($args[0]))) {
                    return false;
                }
                if (!($sender instanceof Player)) {
                    $sender->sendMessage("§5[<3] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                $loved       = array_shift($args);
                $lovedPlayer = $this->getServer()->getPlayer($loved);
                if ($lovedPlayer !== null and $lovedPlayer->isOnline()) {
                    $lovedPlayer->sendMessage("§5[<3]§a" . $sender->getName() . "§5has broken up with you!");
                    if (isset($args[0])) {
                        $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                    }
                    $sender->sendMessage("§5[<3] You have broken up with §a" . $loved . "§5.");
                    $this->getServer()->broadcastMessage("§d[<3]§a" . $sender->getName() . " §dhas broken up with §a" . $loved . "§d.");
                    $sender1      = $sender->getName();
                    $lovedPlayer1 = $lovedPlayer->getName();
                    $this->love->remove($sender1);
                    $this->love->remove($lovedPlayer1);
                    $this->love->save();
                    
                    /*NAMETAG THING */
                    $sender->setDisplayName(str_replace(TextFormat::LIGHT_PURPLE . "[<3]", "", $sender->getDisplayName()));
                    $lovedPlayer->setDisplayName(str_replace(TextFormat::LIGHT_PURPLE . "[<3]", "", $lovedPlayer->getDisplayName()));
                    /*NAMETAG THING */
                    
                    return true;
                } else {
                    $sender->sendMessage($loved . "§5[<3] is not avalible for a breakup. Basically, §a" . $loved . "§5 does not exist, or is not online.");
                    return true;
                }
                /**
                 *
                 *                                      NOLOVE
                 *
                 **/
                break;
            case "nolove":
                if (!(isset($args[0]))) {
                    return false;
                }
                if (!($sender instanceof Player)) {
                    $sender->sendMessage("§5[<3] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                if ($args[0] == "nolove") {
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage("§5[<3] You will no longer be loved. §e#ForEverAlone");
                    return true;
                } elseif ($args[0] == "love") {
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage("§5[<3] You will now be loved again! §e#GetInThere");
                    return true;
                } else {
                    return false;
                }
                /**
                 *
                 *                                   SERVERLOVE
                 *
                 **/
                break;
            case "serverlove":
                $sender->sendMessage("§5[<3][ServerLoveMCPE] Original ServerLove (For MCPC )  Made By ratchetgame98 ");
                $sender->sendMessage("§5[<3][ServerLoveMCPE] This plugin was made by TheDeibo");
                $sender->sendMessage("\n§5They are 4 help pages. \nSimply use /serverlove help <1/2/3/4>");
                return true;
                break;
            default:
                if ($args[0] == "") {
                    if (!isset($args[1]) == 1) {
                        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE Help Page 1 of 4" . TextFormat::DARK_PURPLE . "\n/serverlove\nThis is the main command.\n It only shows the infomation about the plugin.");
                        return true;
                    }
                    if ($args[1] == 2) {
                        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE Help Page 2 of 4" . TextFormat::DARK_PURPLE . "\n/love \nUsage: /love <playerName>\n Allows a player to love another Player");
                        return true;
                    }
                    if ($args[1] == 3) {
                        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE Help Page 3 of 4" . TextFormat::DARK_PURPLE . "\n/nolove \nUsage: /nolove <nolove / love>\n Allows the player to toggle if they wish to be loved or not.");
                        return true;
                    }
                    if ($args[1] == 4) {
                        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE Help Page 4 of 4" . TextFormat::DARK_PURPLE . "\n/breakup \nUsage: /breakup <playerName>\n Allows a player to breakup with another Player");
                        return true;
                    }
                } else {
                    $sender->sendMessage(TextFormat::LIGHT_PURPLE . "ServerLoveMCPE Help Page" . TextFormat::DARK_PURPLE . "\nThey are 4 help pages. \nSimply use /serverlove help <1/2/3/4>");
                    return true;
                }
        }
    }
}

<?php

namespace UserFrosting;

/**
 * Description of ProxyController
 *
 * @author Florian
 */
class ProxyController extends \UserFrosting\BaseController {

    public function __construct($app) {
        $this->_app = $app;
    }

    public function genSquidguardConf() {

        $tab = "\t";

        $conf = "################################" . PHP_EOL;
        $conf.="#SquidGuard configuration file" . PHP_EOL;
        $conf.="#Generated by IHMSQUID" . PHP_EOL;
        $conf.="################################" . PHP_EOL;
        $conf.=PHP_EOL;
        $conf.=PHP_EOL;
        $conf.="dbhome /var/lib/squidguard/db/blacklists" . PHP_EOL;
        $conf.="logdir /var/log/ihmsquid/" . PHP_EOL;
        $conf.=PHP_EOL;
        $conf.=PHP_EOL;

        //time
        $horaires = MySqlWorkingHoursLoader::fetch("1");
        $conf.="################################" . PHP_EOL;
        $conf.="#Working Hours" . PHP_EOL;
        $conf.="################################" . PHP_EOL;
        $conf.="time WorkingHours {" . PHP_EOL;
        $conf.=$tab . "weekly mtwhf " . $horaires->hourstartam . "-" . $horaires->hourendam . PHP_EOL;
        $conf.=$tab . "weekly mtwhf " . $horaires->hourstartpm . "-" . $horaires->hourendpm . PHP_EOL;
        $conf.="}" . PHP_EOL;
        $conf.=PHP_EOL;
        $conf.=PHP_EOL;

        //Src
        $salles = MySqlSalleLoader::fetchAll();
        $ips_formateur = "";
        $src = "";
        foreach ($salles as $salle) {
            //Récup ip formateur
            $ips_formateur .= $salle->ip_formateur . " ";

            //Pour chaque salle, création du src
            $src.="src " . str_replace(" ", "_", $salle->name) . " {" . PHP_EOL;
            $src.=$tab . "ip $salle->network/$salle->mask_cidr" . PHP_EOL;
            $src .= $tab . "log $salle->id" . "_logsquidguard" . PHP_EOL;
            $src.="}" . PHP_EOL;
            $src.=PHP_EOL;
        }

        $conf.="################################" . PHP_EOL;
        $conf.="#SRC" . PHP_EOL;
        $conf.="################################" . PHP_EOL;
        $conf.="src formateurs {" . PHP_EOL;
        $conf.=$tab . "ip " . $ips_formateur . PHP_EOL;
        $conf.="}" . PHP_EOL;
        $conf.=PHP_EOL;

        $conf.=$src;
        $conf.=PHP_EOL;

        //dest
        $categories = MySqlBlacklistCategoriesLoader::fetchAll();
        $conf.="################################" . PHP_EOL;
        $conf.="#DEST" . PHP_EOL;
        $conf.="################################" . PHP_EOL;

        foreach ($categories as $category) {
            $conf.="dest $category->category_name {" . PHP_EOL;
            $conf.=$tab . "domainlist $category->category_name/domains" . PHP_EOL;
            $conf.=$tab . "urllist $category->category_name/urls" . PHP_EOL;
            $conf.=$tab . "expressionlist $category->category_name/expressions" . PHP_EOL;
            $conf.="}" . PHP_EOL;
            $conf.=PHP_EOL;
        }

        //blacklist et whitelist définies dans l'application
        $conf.="dest blacklist_ihmsquid {" . PHP_EOL;
        $conf.=$tab . "domainlist blacklist_ihmsquid/domains" . PHP_EOL;
        $conf.=$tab . "urllist blacklist_ihmsquid/urls" . PHP_EOL;
        $conf.=$tab . "expressionlist blacklist_ihmsquid/expressions" . PHP_EOL;
        $conf.="}" . PHP_EOL;
        $conf.=PHP_EOL;
        $conf.="dest whitelist_ihmsquid {" . PHP_EOL;
        $conf.=$tab . "domainlist whitelist_ihmsquid/domains" . PHP_EOL;
        $conf.=$tab . "urllist whitelist_ihmsquid/urls" . PHP_EOL;
        $conf.=$tab . "expressionlist whitelist_ihmsquid/expressions" . PHP_EOL;
        $conf.="}" . PHP_EOL;
        $conf.=PHP_EOL;

        //Niveaux de filtrage personnalisé
        $customfilters = MySqlCustomConfLoader::fetchAllExceptStandard();
        foreach ($customfilters as $customfilter) {
            $conf.="dest " . str_replace(" ", "_", $customfilter->name) . "_ihmsquid {" . PHP_EOL;
            $conf.=$tab . "domainlist " . str_replace(" ", "_", $customfilter->name) . "/domains" . PHP_EOL;
            $conf.=$tab . "urllist " . str_replace(" ", "_", $customfilter->name) . "/urls" . PHP_EOL;
            $conf.=$tab . "expressionlist " . str_replace(" ", "_", $customfilter->name) . "/expressions" . PHP_EOL;
            $conf.="}" . PHP_EOL;
            $conf.=PHP_EOL;
        }

        //Acl
        $blocked_categories = "";
        foreach ($categories as $category) {
            if (!$category->allowed) {
                $blocked_categories.="!$category->category_name ";
            }
        }
        $conf.="################################" . PHP_EOL;
        $conf.="#ACL" . PHP_EOL;
        $conf.="################################" . PHP_EOL;
        $conf.="acl {" . PHP_EOL;
        $conf.=$tab . "formateurs {" . PHP_EOL;
        $conf.=$tab . $tab . "pass all" . PHP_EOL;
        $conf.=$tab . "}" . PHP_EOL;
        foreach ($salles as $salle) {
            $conf.=$tab . str_replace(" ", "_", $salle->name) . " within WorkingHours {" . PHP_EOL;
            if ($salle->id_customconf == 1 || $salle->id_customconf == "") {
                $conf.=$tab . $tab . "pass none" . PHP_EOL;
            } elseif ($salle->id_customconf == 2) {
                $conf.=$tab . $tab . "pass whitelist_ihmsquid !blacklist_ihmsquid $blocked_categories all" . PHP_EOL;
            } else {
                foreach ($customfilters as $customfilter) {
                    if ($customfilter->id == $salle->id_customconf) {
                        $conf.=$tab . $tab . "pass " . str_replace(" ", "_", $customfilter->name) . " none" . PHP_EOL;
                        break;
                    }
                }
            }
            $conf.=$tab . "} else {" . PHP_EOL;
            $conf.=$tab . $tab . "pass whitelist_ihmsquid !blacklist_ihmsquid $blocked_categories all" . PHP_EOL;
            $conf.=$tab . "}" . PHP_EOL;
            $conf.=PHP_EOL;
        }
        $conf.=$tab . "default {" . PHP_EOL;

        $conf.=$tab . $tab . "pass none" . PHP_EOL;
        $conf.=$tab . "}" . PHP_EOL;
        $conf.="}" . PHP_EOL;

        //Ecriture du fichier de conf en local
        $ssh = ssh2_connect(MySqlConfgenLoader::fetch("ip_squid", "libelle")->value);
        ssh2_auth_pubkey_file($ssh, MySqlConfgenLoader::fetch("ssh_user", "libelle")->value, SSH_PUBKEY, SSH_PRIVKEY);
        $path = MySqlConfgenLoader::fetch("squidguard_conf_path", "libelle")->value;
        ssh2_exec($ssh, "sudo sh -c 'echo \"$conf\" > $path'");
        ssh2_exec($ssh, "sudo service squid reload");
//        stream_set_blocking($stream, true);
//        $output = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
//        var_dump(stream_get_contents($output));
    }

    public function update_delay_pools() {
        $max_size = MySqlConfgenLoader::fetch("delay_pool_max_size", "libelle");
        $restore_rate = MySqlConfgenLoader::fetch("delay_pool_restore_rate", "libelle");
        $path = MySqlConfgenLoader::fetch("squid_conf_path", "libelle");
        $sshuser = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $ip = MySqlConfgenLoader::fetch("ip_squid", "libelle");
        //sed en ssh pour remplacer les valeurs
        $ssh = ssh2_connect($ip->value);
        ssh2_auth_pubkey_file($ssh, $sshuser->value, SSH_PUBKEY, SSH_PRIVKEY);
        ssh2_exec($ssh, "sudo sed -i 's/delay_parameters.*/delay_parameters 1 $restore_rate->value\/$max_size->value/' $path->value");
        //reload service squid
        ssh2_exec($ssh, "sudo service squid reload");
    }

    public function gen_bypasslist() {
        //get liste sites
        $bypasslist = MySqlCustomBypasslistLoader::fetchAll();
        $domainlist = "";
        foreach ($bypasslist as $b) {
            $domainlist.="$b->url ";
        }
        str_replace(".", "\.", $domainlist);

        //sed dans le fichier squid.conf
        $path = MySqlConfgenLoader::fetch("squid_conf_path", "libelle");
        $sshuser = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $ip = MySqlConfgenLoader::fetch("ip_squid", "libelle");

        $ssh = ssh2_connect(MySqlConfgenLoader::fetch("ip_squid", "libelle")->value);
        ssh2_auth_pubkey_file($ssh, MySqlConfgenLoader::fetch("ssh_user", "libelle")->value, SSH_PUBKEY, SSH_PRIVKEY);
        ssh2_exec($ssh, "sudo sed -i 's/acl bypass_auth.*/acl bypass_auth dstdomain $domainlist/' $path->value");
//        var_dump($domainlist);
        //reload service squid
        ssh2_exec($ssh, "sudo service squid reload");
    }

    public function gen_customfilter($id) {
        //get customfilter list
        $customfilter = MySqlCustomConfLoader::fetch($id);
//        var_dump($customfilter);
//        exit(0);
        //get path
        $path = MySqlConfgenLoader::fetch("squidguard_db", "libelle");
        //ssh initialisation
        $sshuser = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $ip = MySqlConfgenLoader::fetch("ip_squid", "libelle");
        $ssh = ssh2_connect(MySqlConfgenLoader::fetch("ip_squid", "libelle")->value);
        ssh2_auth_pubkey_file($ssh, MySqlConfgenLoader::fetch("ssh_user", "libelle")->value, SSH_PUBKEY, SSH_PRIVKEY);


        //get items
        $items = MySqlCustomConfItemLoader::fetchAll($customfilter->id, "id_custom_conf");
        $domains = "";
        foreach ($items as $item) {
            $domains.=$item->url . PHP_EOL;
        }
        //delete existing
        $dirpath = $path->value . "/" . str_replace(" ", "_", $customfilter->name) . "_ihmsquid";

        ssh2_exec($ssh, "rm -f $dirpath");
        //create folder
        ssh2_exec($ssh, "mkdir $dirpath");
        //create files
        ssh2_exec($ssh, "touch $dirpath/domains");
        ssh2_exec($ssh, "touch $dirpath/urls");
        ssh2_exec($ssh, "touch $dirpath/exceptions");
        //populate domainlit
        ssh2_exec($ssh, "sudo sh -c 'echo \"$domains\" > $dirpath/domains'");

        //put good rights
        ssh2_exec($ssh, "sudo chown proxy:proxy -R  $dirpath");
        //generate db

        ssh2_exec($ssh, "sudo squidGuard -C all -d");
        //reload service
        ssh2_exec($ssh, "sudo service squid reload");
    }

    public function update_black_or_white_list($l) {

        $path = MySqlConfgenLoader::fetch("squidguard_db", "libelle");
        //ssh initialisation
        $sshuser = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $ip = MySqlConfgenLoader::fetch("ip_squid", "libelle");
        $ssh = ssh2_connect(MySqlConfgenLoader::fetch("ip_squid", "libelle")->value);
        ssh2_auth_pubkey_file($ssh, MySqlConfgenLoader::fetch("ssh_user", "libelle")->value, SSH_PUBKEY, SSH_PRIVKEY);


        //get items
        if ($l == "black") {
            $items = MySqlCustomBlacklistLoader::fetchAll();
        } else {
            $items = MySqlCustomWhitelistLoader::fetchAll();
        }

        $domains = "";
        foreach ($items as $item) {
            $domains.=$item->url . PHP_EOL;
        }
        var_dump($domains);
        //delete existing
        $dirpath = $path->value . "/" . $l . "list_ihmsquid";

        ssh2_exec($ssh, "rm -f $dirpath");
        //create folder
        ssh2_exec($ssh, "mkdir $dirpath");
        //create files
        ssh2_exec($ssh, "touch $dirpath/domains");
        ssh2_exec($ssh, "touch $dirpath/urls");
        ssh2_exec($ssh, "touch $dirpath/exceptions");
        //populate domainlit
        ssh2_exec($ssh, "sudo sh -c 'echo \"$domains\" > $dirpath/domains'");

        //put good rights
        ssh2_exec($ssh, "sudo chown proxy:proxy -R  $dirpath");
        //generate db
        ssh2_exec($ssh, "sudo squidGuard -C all -d");
        //reload service
        ssh2_exec($ssh, "sudo service squid reload");
    }

}

?>

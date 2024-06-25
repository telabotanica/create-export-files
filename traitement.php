<?php
//create_files_with_complementary_fields("arbresRemarquables","arbres-rem_all_bdtfx.csv","A2380F40-1496-20E0-E053-2614A8C06E36");
//create_files_with_complementary_fields("arbresTetards","arbres-tetards_all_bdtfx.csv","A2380F40-1495-20E0-E053-2614A8C06E36");
create_files_with_complementary_fields("sauvagesDeMaRue","sauvages_all_bdtfx.csv","A2224CDB-4CBB-103B-E053-2614A8C04C7B");
//create_files("plantNet","cel_export_total_plantnet_taxref.csv","A2413E96-351B-0DE0-E053-2614A8C0C52B");
//create_files_with_complementary_fields("messicoles","messicoles_all_bdtfx.csv","A2413E96-3518-0DE0-E053-2614A8C0C52B");
//create_files_with_complementary_fields("missionsFlore","missions_flore.csv","A2413E96-3518-0DE0-E053-2614A8C0C52B");
/**
 * Créée les fichiers normées par programme sans champs complémentaires
 * @param {String} $programme : nomDuProgramme
 * @param {String} $nom_fichier : nom_du_fichier_export.csv
 * @param {String} $id_jdd : uuid du jeu de de données (fiche INPN) 
 */
function create_files($programme,$nom_fichier,$id_jdd){
    
    $fichier = fopen($nom_fichier, "r");
    $champs_file = file("champs_file.csv");
    $array_evt = [];
    $array_suj=[];
    $array_descr =[];
    $array_attribut=[];
    $array_positions_evt=[];
    $array_positions_suj=[];
    $array_positions_descr=[];
    $array_positions_attribut=[];
    $array_noms_attribut=[];
    $cpt =0;
    if ($fichier) {
        while (($line = fgets($fichier)) !== false) {
            
            $parts= explode(";",$line);
            
            if ($cpt ===0){
                //var_dump($parts);
                for($i=0;$i<count($parts);$i++){
                    
                    $parts[$i]=str_replace('"','',$parts[$i]);
                    $parts[$i]=preg_replace("# {2,}#", "", preg_replace("#(\r\n|\n\r|\n|\r)#","",$parts[$i]));
                   
                    $ligne_evt=["idSinpEvenement","idSInpJdd","identite","statutSource","anonymisation","dateExacte","dateImprecise","typeLocalisation","natureObjetGeo","nomLocalisation","transmissionPrecise","pente","exposition","X","Y","codeEPSG"];
                    $ligne_suj=["idSinpSujetObs","idSinpEvenement","statutObservation","nomCite","idOrigineSujetObs","cdNom"];
                    $ligne_descr=["idSinpSujetObs","idSinpDescriptif","etatBiologique","spontaneite","urlPreuveNumerique","objetDenombrement","methodeDenombrement","denombrementMin","denombrementMax","commentaireDescriptif"];
                    $ligne_attribut = ["idSinpEvenement","idSinpSujetObs","nomAttributAdd","defintionAttributAdd","valeurAttributAdd"];
                    if ($parts[$i]==="pseudo_utilisateur" OR $parts[$i] === "station" OR $parts[$i]==='latitude' OR $parts[$i]==='longitude' OR $parts[$i]==="date_observation"){
                        array_push($array_positions_evt,$i);
                    }else if($parts[$i] === "id_observation" OR $parts[$i] === "nom_ret" OR $parts[$i] === "cd_nom"){
                        array_push($array_positions_suj,$i);

                    }else if($parts[$i] === "commentaire" OR $parts[$i] === "abondance" OR $parts[$i] === "spontaneite" OR $parts[$i] === "images" ){
                        array_push($array_positions_descr,$i);
                    }else if($parts[$i] === "nom_sel_nn" OR $parts[$i] === "nom_referentiel" OR $parts[$i] === "certitude" OR $parts[$i] === "milieu"){
                        array_push($array_positions_attribut,$i);
                        array_push($array_noms_attribut,$parts[$i]);
                    }
                }
                
                array_push($array_evt,$ligne_evt);
                array_push($array_suj,$ligne_suj);
                array_push($array_descr,$ligne_descr);
                array_push($array_attribut,$ligne_attribut);
                

            }else{
                //var_dump($parts);
                $id_evt = guidv4();
                $id_suj = guidv4();
                for($i=0;$i<count($parts);$i++){
                    $parts[$i] = str_replace('"','',$parts[$i]);
                    $parts[$i]= preg_replace("# {2,}#", "", preg_replace("#(\r\n|\n\r|\n|\r)#","",$parts[$i]));    
                        
                        if (in_array($i,$array_positions_attribut)){
                            $ligne_arr_attribut = [];
                            $pos=array_search($i, $array_positions_attribut);
                            $nom_col = $array_noms_attribut[$pos];
                            
                            array_push($ligne_arr_attribut,$id_evt);
                            array_push($ligne_arr_attribut,$id_suj);

                            array_push($ligne_arr_attribut,$nom_col);
                            foreach($champs_file as $ligne){
                                if (str_contains($ligne,$nom_col)){
                                    $def = explode(";",$ligne)[1];
                                    
                                    array_push($ligne_arr_attribut,$def);
                                }
                            }
                            
                            array_push($ligne_arr_attribut,$parts[$i]);
                            array_push($array_attribut,$ligne_arr_attribut);
                        }
                        

                }
                $pseudo = $parts[$array_positions_evt[0]];
                
                $station = $parts[$array_positions_evt[1]];
                
                $latitude = $parts[$array_positions_evt[2]];
                
                $longitude = $parts[$array_positions_evt[3]];
                
                $date = $parts[$array_positions_evt[4]];

                $ligne_arr_evt = [];
            
                array_push($ligne_arr_evt,$id_evt);
                array_push($ligne_arr_evt,$id_jdd);
                array_push($ligne_arr_evt,$pseudo);
                array_push($ligne_arr_evt,"Te");
                array_push($ligne_arr_evt,"false");
                array_push($ligne_arr_evt,$date);
                array_push($ligne_arr_evt,"false");
                array_push($ligne_arr_evt,"5");
                array_push($ligne_arr_evt,"St");
                array_push($ligne_arr_evt,$station);
                array_push($ligne_arr_evt,"true");
                array_push($ligne_arr_evt,"");
                array_push($ligne_arr_evt,"");
                array_push($ligne_arr_evt,$latitude);
                array_push($ligne_arr_evt,$longitude);
                array_push($ligne_arr_evt,"4326");
                array_push($array_evt,$ligne_arr_evt);

                $idObs = $parts[$array_positions_suj[0]];
                $nom = $parts[$array_positions_suj[1]];
                $nom_req = str_replace(" ","%20",$nom);
                $nom_req = str_replace("[","%5B",$nom_req);
                $nom_req = str_replace("]","%5D",$nom_req);
                $cdNom = $parts[$array_positions_suj[2]];
                $url = "https://taxref.mnhn.fr/api/taxa/fuzzyMatch?term=$nom_req";
                
                if ($cdNom === "NULL"){
                    $curl = curl_init( $url );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec( $curl );
                    curl_close( $curl );
                    $res=json_decode($response);
                    
                    $cdNom = $res->_embedded->taxa[0]->referenceId;
                    
                }

                $ligne_arr_suj=[];
                
                array_push($ligne_arr_suj,$id_suj);
                array_push($ligne_arr_suj,$id_evt);
                array_push($ligne_arr_suj,2);
                array_push($ligne_arr_suj,$nom);
                array_push($ligne_arr_suj,$idObs);
                array_push($ligne_arr_suj,$cdNom);
                array_push($array_suj,$ligne_arr_suj);

                $ligne_arr_descr=[];
                array_push($ligne_arr_descr,$id_suj);
                $id_descr = guidv4();
                array_push($ligne_arr_descr,$id_descr);
                $comm = $parts[$array_positions_descr[0]];
                if (str_contains($comm,"mort")){
                    $etat = 3;
                }else{
                    $etat = 1;
                }
                array_push($ligne_arr_descr,$etat);
                $spontaneite = $parts[$array_positions_descr[2]];
                array_push($ligne_arr_descr,$spontaneite);
                $image = $parts[$array_positions_descr[3]];
                array_push($ligne_arr_descr,$image);
                array_push($ligne_arr_descr,"IND");
                array_push($ligne_arr_descr,"Co");
                $abondance = $parts[$array_positions_descr[1]];
                array_push($ligne_arr_descr,$abondance);
                array_push($ligne_arr_descr,$abondance);
                array_push($ligne_arr_descr,$comm);
                array_push($array_descr,$ligne_arr_descr);

            }
            
            
            $cpt++;
        }

        fclose($fichier);
        $fp = fopen("Evenement_$programme.csv", 'wb');
        foreach ($array_evt as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("SujetObservation_$programme.csv", 'wb');
        foreach ($array_suj as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("DescriptifSujet_$programme.csv", 'wb');
        foreach ($array_descr as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("AttributsAdditionnels_$programme.csv", 'wb');
        foreach ($array_attribut as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);
    }
}

/**
 * Créée les fichiers normées par programme avec champs complémentaires
 * @param {String} $programme : nomDuProgramme
 * @param {String} $nom_fichier : nom_du_fichier_export_csv
 * @param {String} $id_jdd : uuid du jeu de de données (fiche INPN) 
 */
function create_files_with_complementary_fields($programme,$nom_fichier,$id_jdd){
   
    $fichier = fopen($nom_fichier, "r");
    $champs_file = file("champs_file.csv");
    $array_evt = [];
    $array_suj=[];
    $array_descr =[];
    $array_attribut=[];
    $array_positions_evt=[];
    $array_positions_suj=[];
    $array_positions_descr=[];
    $array_positions_attribut=[];
    $array_noms_champs_comp=[];
    $array_noms_attribut=[];
    $cpt =0;
    $nb_columns=0;
    if ($fichier) {
        while (($line = fgets($fichier)) !== false) {
            
            $parts= explode(";",$line);
            
            if ($cpt ===0){
                $parts= explode(";",$line);
                $nb_columns = count($parts);
                for($i=0;$i<count($parts);$i++){
                    
                    $parts[$i]=str_replace('"','',$parts[$i]);
                    $parts[$i]=preg_replace("# {2,}#", "", preg_replace("#(\r\n|\n\r|\n|\r)#","",$parts[$i]));
                    $ligne_evt=["idSinpEvenement","idSInpJdd","identite","statutSource","anonymisation","dateExacte","dateImprecise","typeLocalisation","natureObjetGeo","nomLocalisation","transmissionPrecise","pente","exposition","X","Y","codeEPSG"];
                    $ligne_suj=["idSinpSujetObs","idSinpEvenement","statutObservation","nomCite","idOrigineSujetObs","cdNom"];
                    $ligne_descr=["idSinpSujetObs","idSinpDescriptif","etatBiologique","spontaneite","urlPreuveNumerique","objetDenombrement","methodeDenombrement","denombrementMin","denombrementMax","commentaireDescriptif"];
                    $ligne_attribut = ["idSinpEvenement","idSinpSujetObs","nomAttributAdd","defintionAttributAdd","valeurAttributAdd"];
                    if ($parts[$i]==="pseudo_utilisateur" OR $parts[$i] === "station" OR $parts[$i]==='latitude' OR $parts[$i]==='longitude' OR $parts[$i]==="date_observation"){
                        array_push($array_positions_evt,$i);
                    }else if($parts[$i] === "id_observation" OR $parts[$i] === "nom_ret" OR $parts[$i] === "cd_nom"){
                        array_push($array_positions_suj,$i);

                    }else if($parts[$i] === "commentaire" OR $parts[$i] === "abondance" OR $parts[$i] === "spontaneite" OR $parts[$i] === "images" ){
                        array_push($array_positions_descr,$i);
                    }else if($parts[$i] === "nom_sel_nn" OR $parts[$i] === "nom_referentiel" OR $parts[$i] === "certitude" OR $parts[$i] === "milieu"){
                        array_push($array_positions_attribut,$i);
                        array_push($array_noms_attribut,$parts[$i]);
                    }else if($i===count($parts)-1){
                    
                        $array = explode(",",$parts[$i]);
                        foreach ($array as $item){
                            $item = str_replace($programme,"",$item);
                            if (!in_array($item,$array_noms_champs_comp)){
                                array_push($array_noms_champs_comp,$item);
                            }
                            
                        }
                    }
                }
                
                array_push($array_evt,$ligne_evt);
                array_push($array_suj,$ligne_suj);
                array_push($array_descr,$ligne_descr);
                array_push($array_attribut,$ligne_attribut);

              /*   var_dump($array_positions_evt);
                var_dump($array_positions_suj);
                var_dump($array_positions_descr);
                var_dump($array_positions_attribut);
                var_dump($nb_columns); */
            }else{
                
                
                $parts= explode(";",$line,$nb_columns);
                /* if($nb_columns>count($parts)){
                    die();
                } */
                $id_evt = guidv4();
                $id_suj = guidv4();
                for($i=0;$i<count($parts);$i++){
                    $parts[$i] = str_replace('"','',$parts[$i]);
                    $parts[$i]= preg_replace("# {2,}#", "", preg_replace("#(\r\n|\n\r|\n|\r)#","",$parts[$i]));    
                        
                        if (in_array($i,$array_positions_attribut)){
                            $ligne_arr_attribut = [];
                            $pos=array_search($i, $array_positions_attribut);
                            $nom_col = $array_noms_attribut[$pos];
                            
                            array_push($ligne_arr_attribut,$id_evt);
                            array_push($ligne_arr_attribut,$id_suj);

                            array_push($ligne_arr_attribut,$nom_col);
                            foreach($champs_file as $ligne){
                                if (str_contains($ligne,$nom_col)){
                                    $def = explode(";",$ligne)[1];
                                    
                                    array_push($ligne_arr_attribut,$def);
                                }
                            }
                            
                            array_push($ligne_arr_attribut,$parts[$i]);
                            array_push($array_attribut,$ligne_arr_attribut);
                        }else if($i === count($parts)- 1 AND !str_contains($parts[$i],"NULL")){

                            $parts[$i] = str_replace(", "," ",$parts[$i]);
                            
                            $parts[$i] = replace_decimal_commas($parts[$i]);
                            
                            $comp_arr = explode(",",$parts[$i]);
                            $array_noms_champs = [];
                            for($j=0;$j<count($array_noms_champs_comp);$j++){
                                if (isset($comp_arr[$j]) AND !empty($comp_arr[$j])){
                                
                                    $ligne_arr_attribut = [];
                                    array_push($ligne_arr_attribut,$id_evt);
                                    array_push($ligne_arr_attribut,$id_suj);
                                    
                                    $champ = explode(":",$comp_arr[$j],2);
                                    $nom_champ=$champ[0];
                                    if (isset($champ[1])){
                                        $valeur = $champ[1];
                                    }else{
                                        $valeur="";
                                    }
                                    
                                   
                                    $nom_champ = str_replace('"','',$nom_champ);
                                    $nom_champ = str_replace("'","",$nom_champ);
                                    $nom_champ = trim($nom_champ);
                                    
                                    if (!in_array($nom_champ,$array_noms_champs)){

                                        array_push($ligne_arr_attribut,$nom_champ);
                                        foreach($champs_file as $ligne){
                                            $line_parts=explode(";",$ligne);
                                            var_dump($line_parts);
                                            $line_name=trim($line_parts[0]);
                                            var_dump($line_name);
                                            var_dump($nom_champ);
                                            if ($line_name === $nom_champ){
                                                
                                                $def = $line_parts[1];
                                                array_push($array_noms_champs,$nom_champ);
                                                array_push($ligne_arr_attribut,$def);
                                                
                                            }
                                        }
                                    
                                        array_push($ligne_arr_attribut,$valeur);
                                        array_push($array_attribut,$ligne_arr_attribut);
                                    }
                                }
                                
                            }
                            
                        }
                        

                }
                $pseudo = $parts[$array_positions_evt[0]];
                
                $station = $parts[$array_positions_evt[1]];
                
                $latitude = $parts[$array_positions_evt[2]];
                
                $longitude = $parts[$array_positions_evt[3]];
                
                $date = $parts[$array_positions_evt[4]];

                $ligne_arr_evt = [];
            
                array_push($ligne_arr_evt,$id_evt);
                array_push($ligne_arr_evt,$id_jdd);
                array_push($ligne_arr_evt,$pseudo);
                array_push($ligne_arr_evt,"Te");
                array_push($ligne_arr_evt,"false");
                array_push($ligne_arr_evt,$date);
                array_push($ligne_arr_evt,"false");
                array_push($ligne_arr_evt,"5");
                array_push($ligne_arr_evt,"St");
                array_push($ligne_arr_evt,$station);
                array_push($ligne_arr_evt,"true");
                array_push($ligne_arr_evt,"");
                array_push($ligne_arr_evt,"");
                array_push($ligne_arr_evt,$latitude);
                array_push($ligne_arr_evt,$longitude);
                array_push($ligne_arr_evt,"4326");
                array_push($array_evt,$ligne_arr_evt);

                $idObs = $parts[$array_positions_suj[0]];
                $nom = $parts[$array_positions_suj[1]];
                $nom_req = str_replace(" × "," ",$nom);
                $nom_req = str_replace(" ","%20",$nom_req);
                $name=explode("[",$nom_req)[0];
                
                $cdNom = $parts[$array_positions_suj[2]];
                $url = "https://taxref.mnhn.fr/api/taxa/fuzzyMatch?term='$name'";
                
                if ($cdNom === "NULL" AND !str_contains($name,"aceae")){
                    var_dump($cpt." ".$url);
                    $curl = curl_init( $url );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec( $curl );
                    curl_close( $curl );
                    $res=json_decode($response);
                    
                    $cdNom = $res->_embedded->taxa[0]->referenceId;
                    
                }

                $ligne_arr_suj=[];
                
                array_push($ligne_arr_suj,$id_suj);
                array_push($ligne_arr_suj,$id_evt);
                array_push($ligne_arr_suj,2);
                array_push($ligne_arr_suj,$nom);
                array_push($ligne_arr_suj,$idObs);
                array_push($ligne_arr_suj,$cdNom);
                array_push($array_suj,$ligne_arr_suj);

                $ligne_arr_descr=[];
                array_push($ligne_arr_descr,$id_suj);
                $id_descr = guidv4();
                array_push($ligne_arr_descr,$id_descr);
                $comm = $parts[$array_positions_descr[0]];
                if (str_contains($comm,"mort")){
                    $etat = 3;
                }else{
                    $etat = 1;
                }
                array_push($ligne_arr_descr,$etat);
                $spontaneite = $parts[$array_positions_descr[2]];
                array_push($ligne_arr_descr,$spontaneite);
                $image = $parts[$array_positions_descr[3]];
                array_push($ligne_arr_descr,$image);
                array_push($ligne_arr_descr,"IND");
                array_push($ligne_arr_descr,"Co");
                $abondance = $parts[$array_positions_descr[1]];
                array_push($ligne_arr_descr,$abondance);
                array_push($ligne_arr_descr,$abondance);
                array_push($ligne_arr_descr,$comm);
                array_push($array_descr,$ligne_arr_descr);

            }
            
            
            $cpt++;
        }

        fclose($fichier);
        $fp = fopen("Evenement_$programme.csv", 'wb');
        foreach ($array_evt as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("SujetObservation_$programme.csv", 'wb');
        foreach ($array_suj as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("DescriptifSujet_$programme.csv", 'wb');
        foreach ($array_descr as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);

        $fp = fopen("AttributsAdditionnels_$programme.csv", 'wb');
        foreach ($array_attribut as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line,";");
        }
        fclose($fp);
    }
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function replace_decimal_commas($text) {
    // Expression régulière pour trouver les nombres décimaux
    $pattern = '/\b\d+,\d+\b/';

    // Fonction de rappel pour remplacer la virgule par un point
    $callback = function($matches) {
        return str_replace(',', '.', $matches[0]);
    };

    // Utiliser preg_replace_callback pour appliquer la fonction de rappel aux nombres décimaux
    return preg_replace_callback($pattern, $callback, $text);
}




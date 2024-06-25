Script pour créer les fichiers exports aux normes de l'INPN. A la fin de son exécution, quatre fichiers sont créés pour chaque export : AttributsAdditionnels, Evenement, SujetObservation et DescriptifSujet.
1. Créer les fichiers de données brutes avec les requêtes de la documentation du wiki (partie Post Traitement). Pour Plantnet et Hors Programme, utilisez les requêtes "Tous sauf les programmes" et "Seulement Plantnet". Attention : vérifierz bien que les contraintes sont les mêmes (dates, référentiel...).
2. Exportez les données de chaque requête dans un fichier "csv for MS excel" puis ouvrez les fichiers avec SublimeText pour les enregistrer avec l'encodage UTF-8.
3. Avec Libroffice, ouvrir chaque fichier (séparation ;).
4. Tapez CTRL + H et remplacez tous les "\n" par une espace. Remplacez ensuite tous les " par '. Sauvegardez les fichiers et copiez-les dans le répertoire du script.
5. Dans le fichier traitement.php, appelez la méthode create_files($nom_du_programme,$nom_du_fichier,$id_du_jeu_de_donnees) pour Plantnet et HorsProgramme et create_files_with_complementary_fields($nom_du_programme,$nom_du_fichier,$id_du_jeu_de_donnees) pour tous les autres.
   Les id du jeu de données sont ceux des fichiers INPN qui ont été créés. Les liens sont dans la documentation. Comme le traitement peut être très long, appelez les méthodes les unes après les autres. 
   

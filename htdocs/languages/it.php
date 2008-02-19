<?php

define ("MAIL_FOOTER",<<<EOF
Per iniziare a partecipare
===============

Per informazioni su come costruire un nuovo nodo visita il nostro wiki a questa pagina:

http://wiki.ninux.org/NuovoNodo

Se vuoi metterti in contatto con noi puoi iscriverti alla mailing list
appropriata (http://wiki.ninux.org/MailingLists ) o venire ad una delle
nostre riunioni (http://wiki.ninux.org/Riunioni ) e conoscere il
gruppo di persona.

Ti aspettiamo!

Saluti,

Ninux.org Community
EOF
);

//index.php
define ("LINK_TO_THIS_PAGE","Collegamento a questa pagina");
define ("NETWORK_MAP","Mappa della rete");
define ("FIND_LOCATION","Trova Indirizzo");
define ("WELCOME_TITLE","Benvenut*!");
define ("WELCOME_TO_THE","Benvenut* alla mappa della rete " . ORG_NAME . "!");
define ("WHAT_IS","Cos'&egrave; " . ORG_NAME . "?");
define ("HOW_TO_USE_MAP","Come si usa questa mappa?");
define ("ADDRESS_LABEL","Indirizzo, via e citt&agrave;, stato o codice postale:");
define ("ADDRESS_SUBMIT_LABEL","Trova");
define ("MAP_SETTINGS_TITLE","Impostazioni della mappa");
define ("SHOW_ACTIVE_NODES","Visualizza nodi attivi");
define ("SHOW_POTENTIAL_NODES","Visualizza ubicazione dei nodi potenziali");
define ("SHOW_WIRELESS_LINKS","Visualizza collegamenti wireless");
define ("SHOW_INTERNET_TUNNELS","Visualizza collegamenti via tunnel su Internet");
define ("NODES_","Nodi");
define ("MY_MARKERS","I miei segnaposto");
define ("LOADING_","Caricando...");

//help.php
define ("THINK_ABOUT","Pensi di costruire un nodo ? Segna la tua posizione sulla mappa!");
define ("THINK_ABOUT_DESC_1","Usa \"" . FIND_LOCATION . "\" qui sotto per aggiungere un segnaposto dove desideri. Puoi anche cliccare direttamente sulla mappa se non conosci l'indirizzo.");
define ("THINK_ABOUT_DESC_2","Rinomina il segnaposto in modo pi&ugrave; significativo: per esempio \"Casa di Eric\".");
define ("THINK_ABOUT_DESC_3","Seleziona l'opzione per aggiungere il segnaposto al database e segui le istruzioni.");
define ("THINK_ABOUT_DESC_4","Puoi cliccare sugli altri nodi per visualizzare foto e altre informazioni. Se pensi di aver trovato un nodo che potresti linkare, contatta il suo proprietario e costruisci un nodo!");
define ("GETTING_STARTED_TEXT","Maggiori informazioni sulla costruzione di un nuovo nodo &raquo;");
define ("MAP_LEGEND_TITLE","Legenda");
define ("MAP_LEGEND_POTENTIAL","Ubicazione di un nodo potenziale");
define ("MAP_LEGEND_ACTIVE","Nodo attivo");

//AddPotentialNode.php
define ("OUT_OF_RANGE","Il punto non pu&ograve; trovarsi pi&ugrave; lontano di %d miglia dal centro della rete.\n");
define ("ADD_NODE","Aggiungi un nodo");
define ("THINKING_ABOUT_NODE","Stai pensando di costruire un nodo qui? Aggiungilo al nostro database! In questo modo altre persone che pensano di potersi collegare con te ti possono contattare."); 
define ("THINKING_ABOUT_NODE_NOTE","NOTA: Aggiungiti alla mappa solo se sei seriamente intenzionato a collegarti con altre persone sulla rete mesh. Lo scopo di questo sito *NON* &egrave; quello di mappare tutti i singoli access point della citt&agrave;"); 
define ("NODE_INFORMATION","Informazioni sul nodo");
define ("LATITUDE_","Latitudine:");
define ("LONGITUDE_","Longitudine:");
define ("ELEVATION_","Altitudine (m s.l.m.):");
define ("NODE_NAME_","Nome del nodo:");
define ("PICK_A_NAME","Scegli un nome per questo nodo, come \"PiazzaTuscolo\" o \"NodoBirreria\"... Qualcosa che contraddistingua la tua posizione."); 
define ("DESCRIPTION_","Descrizione:");
define ("DESCRIPTION_DESC","Inserisci una breve descrizione del luogo (nome dell'associazione, etc.)."); 
define ("NODE_IP_","IP del nodo:");
define ("ENTER_IP","Inserisci l'indirizzo IP del nodo (solo se il nodo &egrave; un nodo attivo della rete). Vedi <a href=\"http://wiki.ninux.org/NuovoNodo\">qui</a>.");
define ("NODE_STREET_ADDRESS_","Indirizzo postale del nodo:");
define ("NODE_STREET_ADDRESS_DESC","Questo &egrave; opzionale e puoi essere vago se preferisci (per esempio specificare il numero civico del palazzo senza l'interno)."); 
define ("YOUR_INFORMATION_","I tuoi dati");
define ("SOMEBODY_NEARBY","Se qualcuno vicino a te pensa di potersi collegare con il tuo nodo, ti deve contattare in qualche modo!");
define ("YOUR_FULL_NAME_","Il tuo nome (e cognome):");
define ("EMAIL_ADDRESS_","Indirizzo e-mail:");
define ("EMAIL_ADDRESS_DESC","Utilizzato per verificare il nodo - deve essere valido.");
define ("PUBLISH_EMAIL_","Pubblica indirizzo e-mail");
define ("JABBER_ID_","Jabber ID:");
define ("JABBER_ID_DESC","Se hai un account <a href=\"http://seattlewireless.net/JabberServer\" target=\"_blank\">Jabber</a>.");
define ("WEBSITE_URL","Home Page:");
define ("WEBSITE_URL_DESC","(Se ne hai una)");

// AddPotentialNodeSubmit.php
define ("INVALID_EMAIL","Indirizzo e-mail non valido.");
define ("INVALID_JABBER","ID jabber non valido.");
define ("INVALID_IP_","IP non valido.");
define ("INVALID_ELEVATION_","Altitudine non valida.");
define ("INVALID_NAME_","Nome non valido.");
define ("ADD_LOCATION_","Aggiungi segnaposto");
define ("SPECIFY_DESCRIPTION","Per favore includi una descrizione di questa ubicazione.");
define ("NODE_ALREADY_EXISTS","Un nodo in questo punto esiste gi&agrave; nel nostro database.");
define ("NODE_NAME_ALREADY_EXISTS","Un nodo con questo nome esiste gi&agrave; nel nostro database.");
define ("ADDNODE_EMAIL_BODY","Grazie per il tuo interesse in %s!

Per prevenire gli abusi, ti chiediamo per favore di cliccare sul seguente link
in modo da confermare il tuo indirizzo e-mail ed aggiungere il tuo segnaposto alla 
mappa della rete.

%s/VerifyNode.php?hash=%s

Se *NON* hai richiesto che un nodo venisse aggiunto alla mappa, o se per qualunque 
ragione vuoi togliere questo segnaposto dalla mappa successivamente, puoi utilizzare
il seguente link:

%s/DeleteNode.php?hash=%s

Dopo la verifica del tuo indirizzo e-mail, puoi aggiornare le informazioni sul nodo 
utilizzando questa pagina:

%s/EditNode.php?hash=%s

%s");
define ("THANK_YOU_","Grazie!");
define ("HELLO_","Ciao");
define ("AN_EMAIL_WAS_SENT","una mail &egrave; stata inviata a");
define ("WITH_INSTRUCTIONS","con le istruzioni per aggiungere il segnaposto alla mappa."); 
define ("GO_BACK_","Indietro");

// DeleteNode.php
define ("ARE_YOU_SURE","Sei <strong>sicuro</strong> di voler eliminare questo nodo?");
define ("YES_DELETE","S&igrave;, eliminalo.");
define ("DONT_DELETE","No, ho cambiato idea.");

// DeleteNodeYes.php
define ("NODE_DELETED_","Nodo eliminato.");
define ("NODE_REMOVED","Il tuo nodo &egrave; stato eliminato dalla mappa.");
define ("VIEW_MAP","Visualizza la mappa!");

// VerifyNode.php
define ("EMAIL_CONFIRMED","Il tuo indirizzo e-mail &egrave; stato verificato e questo segnaposto &egrave; ora attivo sulla mappa!");

// EditNode.php
define ("EDIT_NODE","Modifica i dati del nodo");

// EditNodeSubmit.php
define ("UPDATE_SUCCESSFUL","Informazioni aggiornate con successo.");
?>

<?php
/**
 * @package  api-framework
 * @author   Martin Bean <martin@martinbean.co.uk>
 * @user
 */
class UserController  extends AbstractController {
    
    protected $gdb = null;
    
    function __construct() {
        $this->gdb=userSPDO::singleton();
    }
    
    function usuaris() {
        $sql = "SELECT id,nom,email FROM usuaris";
        $query = $this->gdb->prepare($sql);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    function crearUsuari($request) {
        $nom = $request->parameters["nom"];
        $email = $request->parameters["email"];
        $user = "SELECT nom FROM usuaris WHERE nom = '".$nom."' OR email = '".$email."'";
        $query_u = $this->gdb->prepare($user);
        $query_u->execute();
        $creat = $query_u->fetchAll();
        
        if(!empty($creat))
            return "Usuari o mail ja existents";
        else {
            $sql = "INSERT INTO usuaris(nom, email) VALUES ('".$nom."','".$email."')";
            $query = $this->gdb->prepare($sql);
            $query->execute();
            return "Usuari creat";
        }
    }
    
    function login($request) {
        $nom = $request->parameters["nom"];
        $email = $request->parameters["email"];
        $sql = "SELECT nom FROM usuaris WHERE nom = '".$nom."' AND email = '".$email."'";
        $query = $this->gdb->prepare($sql);
        $query->execute();
        $resultat = $query->fetchAll();
        
        if(!empty($resultat))
            return "Benvingut '".$nom."'";
        else
            return "Login incorrecte";
    }
    
    function actualitzarNom($request) {
        $nom = $request->parameters["nom"];
        
        
            $id = $request->url_elements[1];
            
            $user = 'SELECT id FROM usuaris WHERE id = '.$id.';';;
            $query_u = $this->gdb->prepare($user);
            $query_u->execute();
            $creat = $query_u->fetchAll();

            if(!empty($creat)) {
                $sql = "UPDATE usuaris SET nom = '".$nom."' WHERE id = ".$id.";";
                $query = $this->gdb->prepare($sql);
                $query->execute();
                return "Usuari '".$id."' actualitzat";
            }
            else
                return "Id no trobada";
        }
    
    function esborrarUsuari($request) {
        if(@$request->url_elements[1] != null)
        {
            $id = $request->url_elements[1];

            $user = 'SELECT id FROM usuaris WHERE id = '.$id.';';;
            $query_u = $this->gdb->prepare($user);
            $query_u->execute();
            $creat = $query_u->fetchAll();

            if(!empty($creat)) {
                $sql = "DELETE FROM usuaris WHERE id = ".$id.";";
                $query = $this->gdb->prepare($sql);
                $query->execute();
                return "Usuari '".$id."' eliminat";
            }
            else
                return "Id no trobada";
            }
        else
            return "Cap id seleccionada";
    }
    
    
}

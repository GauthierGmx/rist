# -*- coding: utf-8 -*-
"""
Spyder Editor

This is a temporary script file.
"""

class Activité :
    
    def __init__(self, categorie, prix, coord) : 
        
        self.categorie = categorie
        self.prix = prix
        self.coord = coord      # pourra être changé en adresse  
        

class Utilisateur() : 
    
    def __init__(self, categoriePref, coord, budget) :
        self.categoriePref = categoriePref
        self.coord = coord
        self.budget = budget 
        self.nouveau = True
        

    
    


Pétanque = Activité("sport de boules",
                    10,
                    [43.53881675427716, -1.4874578752491052])

Piscine = Activité("sport de nage",
                    15,
                    [43.549199652585166, -1.4886500797448665])

ZLan = Activité("E-sport",
                0,
                [43.7789654123, -1.4874578752491052])




Axel = Utilisateur("sport de boules",[44.3254147856874,-1.236521445], 30)


listeActivité = [Pétanque,Piscine,ZLan]

def recommandation(utilisateur,liste) : 
    
    # ÉTAPE 1 -> la distance 
    # plus l'activité est proche plus le score sera élevé
    
    
    
    # ÉTAPE 2 ->  le prix (le prix doit etre le plus proche de celui de l'utilisateur ou en dessous)
    
    # ÉTAPE 3 -> les catégories pref
    
    
    
    
    
    
    
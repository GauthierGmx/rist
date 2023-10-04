# -*- coding: utf-8 -*-
"""
Spyder Editor

This is a temporary script file.
"""
import math


class Activité :
    
    def __init__(self, categorie, prix, coord) : 
        
        self.categorie = categorie
        self.prix = prix
        self.coord = coord      # pourra être changé en adresse  
        self.score = 10
        

class Utilisateur() : 
    
    def __init__(self, categoriePref, coord, budget) :
        self.categoriePref = categoriePref
        self.coord = coord
        self.budget = budget 
        self.nouveau = True


Pétanque = Activité("sport de boules",
                    10,
                    [43.49281925929006, -1.4634094451827353])

Piscine = Activité("sport de nage",
                    15,
                    [43.549199652585166, -1.4886500797448665])

ZLan = Activité("E-sport",
                0,
                [44.19396436664341, -0.9408293479724416])


Axel = Utilisateur("sport de boules",[44.21374590883253, -0.9168396728192396], 30)


listeActivité = [Pétanque,Piscine,ZLan]

def distance_haversine(unUtilisateur, Uneactivite):
    
    
    # Convertir les coordonnées degrés en radians
    lat1 = math.radians(unUtilisateur.coord[0])
    lon1 = math.radians(unUtilisateur.coord[1])
    lat2 = math.radians(Uneactivite.coord[0])
    lon2 = math.radians(Uneactivite.coord[1])

    # Calculer la différence de latitude et de longitude entre les deux points
    dlat = lat2 - lat1
    dlon = lon2 - lon1

    # Utiliser la formule haversine pour calculer la distance
    a = math.sin(dlat / 2) ** 2 + math.cos(lat1) * math.cos(lat2) * math.sin(dlon / 2) ** 2
    c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))

    # Rayon de la Terre en kilomètres
    R = 6371

    # Calculer la distance
    distance = R * c
    return distance


def recommandationFroids(utilisateur,liste) : 
    
    """ÉTAPE 1 -> la distance 
    
    Plus l'activité est proche plus le score sera élevé :
        - distance < 15km -- score de base * 2
        - 15km <= distance < 50km -- score de base * 1.8
        - 50km <= distance <100km -- score de base * 1.5
        - distance >= 100km  -- score de base * 1
    
    
    CALCUL DE LA DISTANCE ENTRE DEUX POINTS SUR TERRE :
    ACOS(SIN(RADIANS(B2))*SIN(RADIANS(B3))+COS(RADIANS(B2))*COS(RA DIANS(B3))*COS(RADIANS(C2-C3)))*6371
    """
    
    for activite in liste :
        
        dist = distance_haversine(utilisateur, activite)
        
        if dist <= 15 :
            activite.score *= 2
            
        if 15 < dist <= 50 :
            activite.score *= 1.8
        
        if 50 < dist <= 100 :
            activite.score *= 1.5
            
        
        

    
    
    # ÉTAPE 2 ->  le prix (le prix doit etre le plus proche de celui de l'utilisateur ou en dessous)
    
    # ÉTAPE 3 -> les catégories pref
    
    
 
'''
TEST DISTANCES
'''

recommandationFroids(Axel,listeActivité)    
    
    
    
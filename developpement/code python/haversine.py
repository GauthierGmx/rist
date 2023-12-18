import math
    

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
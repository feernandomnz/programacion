import random
#variables
contador_usuario = 0
contador_maquina = 0
eleccion_maquina = 0
eleccion_usuario = 0
#opciones que tiene el usuario + si pone un numero que no este del 1 al 3 repetir el proceso
while True:
    eleccion_usuario = int(input('1-piedra, 2-papel o 3-tijera: '))
    eleccion_maquina = random.randint(1, 3)
    while eleccion_usuario not in [1, 2, 3]:
        print('ponga un numero del 1 al 3')
        eleccion_usuario = int(input('Ingrese su elección (1, 2 o 3): '))
#posibles resultados
    if eleccion_usuario == eleccion_maquina:
        resultado = 'Empate'
    elif (eleccion_usuario == 1 and eleccion_maquina == 3) or (eleccion_usuario == 2 and eleccion_maquina == 1) or (eleccion_usuario == 3 and eleccion_maquina == 2):
        resultado = 'Ganaste'
        contador_usuario += 1
    else:
        resultado = 'Perdiste'
        contador_maquina += 1
#poner quien ganó y el contador
    print(f'Resultado: {resultado}')
    print(f'Tú: {contador_usuario} vs Máquina: {contador_maquina}')
#quien llegue a tres gana
    if contador_usuario == 3:
        print('ganaste')
        break
    elif contador_maquina == 3:
        print('perdiste')  
        break
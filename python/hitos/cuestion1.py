# variables
area = 0 
base = 0
lado = 0
altura = 0
perimetro = 0
#opciones que tiene el usuario 
while True:
    respuesta = int(input('1-cuadrado 2-rectangulo 3-salir: '))
#preguntar cuanto mide el lado y hacer las operaciones para calcular area y perimetro ,añadir un for para que nos haga la figura con *
    while True:
        if respuesta == 1:
            lado = int(input('¿cuanto mide en lado del cuadrado?: '))
            area = lado * lado
            perimetro = lado * 4
            print(f'este es el area del cuadrado {area}')
            print(f'este es el perimetro del cuadrado {perimetro}')
            for cuadrado in range(0, lado):
                print('*' * lado)
            break
#preguntar cuanto mide la base y la altura para hacer las operaciones y calcular el area y perimetro, añadir un for para que nos haga la figura con *
        elif respuesta == 2:
            base = int(input('¿cuanto mide la base del rectangulo?: '))
            altura = int(input('¿cuanto mide la altura del rectangulo?: '))
            area = base * altura
            perimetro = 2 * (base + altura)
            print (f'este es el area del cuadrado {area}')
            print (f'este es el perimetro del cuadrado {perimetro}')
            for rectangulo in range(0, altura):
                print('*' * base)
            break
#si pone 3 salir al menu  nuevamente
        elif respuesta == 3:
            print('adios')
            break
        else:
            print('elige entre las tres opciones que hay')
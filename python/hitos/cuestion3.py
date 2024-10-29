#variables
saldo = 0
ingresos = 0
retiradas = 0
#pedimos que nos muestre el saldo e su cuenta y ponemos el float para poder poner decimales
while True:
    saldo = float(input('muestrame el saldo de su cuenta (no puede ser menos de 0): '))
    if saldo <= 0:
        print('ponga un numero valido')
        saldo = float(input('muestreme el saldo de su cuenta (no puede ser menos de 0): '))
#opciones del menu+ poner todas las respuestas que hay y añadir otro while para estadisticas
    else:
        while True:
            opciones = int(input('1-ingresar dinero  2-retirar dinero  3-mostrar saldo  4-salir  5-estadisticas: '))
            while opciones not in [1,2,3,4,5]:
                print('ponga un numero del 1 al 5')
                opciones = int(input('1-ingresar dinero  2-retirar dinero  3-mostrar saldo  4-salir: '))
#pedir que deposite el dinero que tenga en la cuenta y poner cuanto ha depositado y lo que tiene en la cuenta
            if opciones == 1:
                cantidad = float(input('deposite su dinero: '))
                if cantidad < 0:
                    print('la cantidad introducida no es valida')
                else:
                    saldo += cantidad
                    ingresos += 1
                    print(f'dinero deposiado: {cantidad} saldo actual: {saldo}')
#pedir que retire la cantidad deseada y poner cuanto ha retirado y su nuevo saldo en la cuenta
            elif opciones == 2:
                cantidad = float(input('cuanto dinero le gustaria retirar: '))
                if cantidad < 0:
                    print('la cantidad introducida no es valida')
                else:
                    saldo -= cantidad
                    retiradas += 1
                    print(f'se ha retirado: {cantidad} saldo actual: {saldo}')
#poner el saldo
            elif opciones == 3:
                    print(f'su saldo es de: {saldo}')
#si pulsa 4 salir
            elif opciones == 4:
                print('hasta pronto')
                break
#poner los logs de retiradas 
            elif opciones == 5:
                print(f'ingresos realizados: {ingresos}')
                print(f'retiradas realizadas: {retiradas}')
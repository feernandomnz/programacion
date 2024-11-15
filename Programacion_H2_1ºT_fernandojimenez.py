#datos iniciales
clientes = {}

productos = {
    1: {'nombre': 'Producto A', 'precio': 10.0},
    2: {'nombre': 'Producto B', 'precio': 20.0},
    3: {'nombre': 'Producto C', 'precio': 30.0},
}
pedidos = {}

#función para registrar un nuevo cliente
def registrar_cliente():
    print('registro de cliente')
    correo = input('ingrese su correo: ')
    if correo in clientes:
        print('este cliente ya esta registrado')  
        return
    nombre = input('ingrese su nombre: ') 
    telefono = input('ingrese su teléfono: ')
#almacenamos el cliente en el diccionario usando el correo como clave
    clientes[correo] = {'nombre': nombre, 'telefono': telefono}
    print(f'Cliente {nombre} registrado exitosamente.')

# Función para visualizar todos los clientes registrados
def visualizar_clientes():
    print('clientes Rregistrados')
    for correo, datos in clientes.items():  
#mostramos toda la información del cliente
        print(f'Correo: {correo}, Nombre: {datos["nombre"]}, Teléfono: {datos["telefono"]}')

#función para realizar una compra
def realizar_compra():
    print('realizar Compra')
    correo = input('ingrese el correo del cliente: ')  
    if correo not in clientes:
        print('este cliente no esta registrado') 
        return
    print('productos disponibles')
#mostramos el catálogo de productos disponibles con su ID, nombre y precio
    for id_prod, info in productos.items():
        print(f'{id_prod}. {info["nombre"]} - {info["precio"]:.2f}')
    seleccion = input('ingrese los IDs de los productos separados por comas: ')  
    seleccion = seleccion.split(',')  
    try:
#convertimos los IDs a enteros
        seleccion = [int(id_prod) for id_prod in seleccion]
    except ValueError:
        print('selcción incorrecta') 
        return
#filtramos los productos seleccionados que existen en el catálogo
    compra = [productos[id_prod] for id_prod in seleccion if id_prod in productos]
    if not compra:
        print('este producto es no esta en la lisa')  
        return
    numero_pedido = len(pedidos) + 1
#guardamos la compra en el diccionario de pedidos con el número de pedido y los datos del cliente y productos
    pedidos[numero_pedido] = {'cliente': clientes[correo], 'productos': compra}
    print(f'compra realizada exitosamente este es su número de pedido: {numero_pedido}')  

#función para hacer el seguimiento de una compra
def seguimiento_compra():
    print('seguimiento de compra')
#solicitar el número de pedido para buscarlo y si el número no existe mostrar error
    numero_pedido = int(input('ingrese el número de pedido: '))
    if numero_pedido not in pedidos:
        print('pedido inexistente')  
        return
    pedido = pedidos[numero_pedido]
#mostramos la información del cliente que realizó el pedido
    print(f'cliente: {pedido["cliente"]["nombre"]}')
    print('productos:')
    for prod in pedido['productos']:
        print(f'- {prod["nombre"]} - {prod["precio"]:.2f}')

#menú principal de la aplicación donde mostraremos todas las opciones y llamamos a las funciones correspondientess
def menu():
    while True:
        print('menú')
        print('1 - registrar cliente')
        print('2 - visualizar clientes registrados')
        print('3 - realizar una compra')
        print('4 - seguimiento de compra')
        print('5 - salir')
        opcion = input('seleccione una opción: ')
        if opcion == '1':
            registrar_cliente()  
        elif opcion == '2':
            visualizar_clientes()  
        elif opcion == '3':
            realizar_compra()  
        elif opcion == '4':
            seguimiento_compra() 
        elif opcion == '5':
            print('saliendo')  
            break
        else:
            print('opcion no valida por favor ingrese un numero del 1 al 5') 

#ejecutamos el programa
menu() 

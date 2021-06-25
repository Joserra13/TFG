import serial

arduino = serial.Serial('/dev/ttyACM0', 9600)

while True:
    state = arduino.readline()
    #print(state[0])
    if (state[0] == '1'):
        print("1")
        break
    elif (state[0] == '0'):
        print("0")
        break
arduino.close() #Finalizamos la comunicacion

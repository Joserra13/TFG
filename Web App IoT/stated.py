import serial

arduino = serial.Serial('/dev/ttyACM0', 9600)

i = 0

while True:
    state = arduino.readline()
    
    if(state[0] != None and i == 0):
        stated = state[0]
        #print("Estado: ")
        #print(state[0])
        i=1
    elif(state[0] != None and i == 1):
        times = state
        #print("Times: ")
        #print(state[0])
        print(times)
        break
arduino.close() #Finalizamos la comunicacion

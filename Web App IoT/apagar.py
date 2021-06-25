import serial

arduino = serial.Serial('/dev/ttyACM0', 9600)

comando = 'L' #Input
arduino.write(comando) #Send the command to Arduino

print('LED OFF')
   
arduino.close() #End the communication
    
     
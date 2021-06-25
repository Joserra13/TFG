import serial

arduino = serial.Serial('/dev/ttyACM0', 9600)

comando = 'H' #Input
arduino.write(comando) #Send the command to Arduino

print("LED ON")        
   
arduino.close() #End the communication
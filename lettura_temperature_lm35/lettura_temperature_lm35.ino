int pin_led_red = 7;
int pin_led_green = 8;
int pin_led_white = 9;
int pin_temp = A0;

void setup()
{
  Serial.begin(9600);
  pinMode(pin_led_red, OUTPUT);
  pinMode(pin_led_white, OUTPUT);
  pinMode(pin_led_green, OUTPUT);
}


void loop()
{
  float lettura_adc = analogRead(pin_temp);
  float milli_volt = (lettura_adc * 5000) / 1024.0;
  float temperatura = milli_volt / 10.0;

  if (temperatura > 20) digitalWrite(pin_led_white, HIGH);
  else digitalWrite(pin_led_white, LOW);

  if (temperatura > 25) digitalWrite(pin_led_green, HIGH);
  else digitalWrite(pin_led_green, LOW);

  if (temperatura > 28) digitalWrite(pin_led_red, HIGH);
  else digitalWrite(pin_led_red, LOW);

  Serial.print("[");

  /*Serial.print(lettura_adc);Serial.print("#");
    Serial.print(milli_volt);Serial.print("#");*/
  Serial.print(temperatura);

  Serial.println("]");
  delay(1000);
}

package com.example.ruben.loginmysql;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class Login extends Activity implements OnClickListener {

    // Declaramos las variables
    private EditText user, pass;
    private Button mSubmit, mRegister;

    private ProgressDialog pDialog;

    // Clase JSONParser
    JSONParser jsonParser = new JSONParser();

    // URL del archivo que va a realizar el proceso
    private static final String LOGIN_URL = "http://lordders.esy.es/aplicacion/login.php";

    // La respuesta del JSON es
    private static final String TAG_SUCCESS = "success";
    private static final String TAG_MESSAGE = "message";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        // Layout al que irá
        setContentView(R.layout.login);

        // setup input fields
        // Enlazamos a las variables los campos que se introducirán
        user = (EditText) findViewById(R.id.username);
        pass = (EditText) findViewById(R.id.password);

        // setup buttons
        mSubmit = (Button) findViewById(R.id.login);
        mRegister = (Button) findViewById(R.id.register);

        // register listeners
        mSubmit.setOnClickListener(this);
        mRegister.setOnClickListener(this);

    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.login:
                new AttemptLogin().execute();
                break;
            case R.id.register:
                // Si pulsa el botón Register, va al layout "Register"
                Intent i = new Intent(this, Register.class);
                startActivity(i);
                break;

            default:
                break;
        }
    }

    class AttemptLogin extends AsyncTask<String, String, String> {

        // Proceso de comprobar credenciales (esperando)
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(Login.this);
            pDialog.setMessage("Comprobando credenciales...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            int success;
            // Declaramos y asignamos el valor introducido a las variables
            String username = user.getText().toString();
            String password = pass.getText().toString();
            try {
                // Building Parameters
                // ArrayList con los valores introducidos para enviar al PHP
                List params = new ArrayList();
                params.add(new BasicNameValuePair("username", username));
                params.add(new BasicNameValuePair("password", password));

                Log.d("request!", "starting");
                // getting product details by making HTTP request
                // Enviamos al archivo PHP mediante POST los valores del último ArrayList
                JSONObject json = jsonParser.makeHttpRequest(LOGIN_URL, "POST",
                        params);

                // check your log for json response
                Log.d("Login attempt", json.toString());

                // json success tag
                success = json.getInt(TAG_SUCCESS);
                // Usuario normal
                if (success == 1) {
                    // Creamos el mensaje de login correcto
                    Log.d("Login correcto!", json.toString());
                    // save user data
                    SharedPreferences sp = PreferenceManager
                            .getDefaultSharedPreferences(Login.this);
                    Editor edit = sp.edit();
                    edit.putString("username", username);
                    edit.commit();

                    // Le dirigimos al layout de "Busqueda"
                    Intent i = new Intent(Login.this, Busqueda.class);
                    finish();
                    startActivity(i);
                    // Mostramos el mensaje creado
                    return json.getString(TAG_MESSAGE);
                }
                    //Usuario admin
                    else if (success == 2) {
                    // Creamos el mensaje de login correcto
                    Log.d("Login correcto!", json.toString());
                    // save user data
                    SharedPreferences sp = PreferenceManager
                            .getDefaultSharedPreferences(Login.this);
                    Editor edit = sp.edit();
                    edit.putString("username", username);
                    edit.commit();

                    // Le dirigimos al layout de "Insertar"
                    Intent ad = new Intent(Login.this, Insertar.class);
                    finish();
                    startActivity(ad);
                } else {
                    // Creamos el mensaje de login fallido
                    Log.d("Fallo en el login!", json.getString(TAG_MESSAGE));
                    // Mostramos el mensaje
                    return json.getString(TAG_MESSAGE);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }

            return null;

        }

        protected void onPostExecute(String file_url) {
            // dismiss the dialog once product deleted
            pDialog.dismiss();
            if (file_url != null) {
                Toast.makeText(Login.this, file_url, Toast.LENGTH_LONG).show();
            }
        }
    }
}
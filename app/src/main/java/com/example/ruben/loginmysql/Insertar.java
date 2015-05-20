package com.example.ruben.loginmysql;

import java.util.ArrayList;
import java.util.List;
import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;
import android.app.Activity;
import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;
import android.widget.AdapterView.OnItemSelectedListener;

public class Insertar extends Activity implements OnClickListener, OnItemSelectedListener {
    // Declaramos las variables
    private EditText name, lastname, note;
    private Button  mEnviar;

    // Progress Dialog
    private ProgressDialog pDialog;

    // JSON parser class
    JSONParser jsonParser = new JSONParser();

    // URL del archivo que va a realizar el proceso
    private static final String REGISTER_URL = "http://lordders.esy.es/aplicacion/insertar.php";

    //ids
    private static final String TAG_SUCCESS = "success";
    private static final String TAG_MESSAGE = "message";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        // Layout al que irá
        setContentView(R.layout.insertar2);

        // Enlazamos a las variables los campos que se introducirán (editText)
        name = (EditText)findViewById(R.id.editText);
        lastname = (EditText)findViewById(R.id.editText2);
        note = (EditText)findViewById(R.id.editText3);

        mEnviar = (Button)findViewById(R.id.enviar);
        mEnviar.setOnClickListener(this);

        // Spinner element
        Spinner spinner = (Spinner) findViewById(R.id.spinner);

        // Spinner click listener
        spinner.setOnItemSelectedListener(this);

        // Spinner Drop down elements
        // ArrayList de las asignaturas
        List<String> asignaturas = new ArrayList<String>();
        asignaturas.add("programacion");
        asignaturas.add("entornos");
        asignaturas.add("bd");
        asignaturas.add("sistemas");
        asignaturas.add("lenguaje");
        asignaturas.add("ingles");
        asignaturas.add("fol");

        // Creating adapter for spinner
        ArrayAdapter<String> dataAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_item, asignaturas);

        // Drop down layout style - list view with radio button
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        // attaching data adapter to spinner
        spinner.setAdapter(dataAdapter);

    }

    String opcionSeleccionada;
    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
        // On selecting a spinner item
        String item = parent.getItemAtPosition(position).toString();
        opcionSeleccionada = item;

        // Showing selected spinner item
        //Toast.makeText(parent.getContext(), "Selected: " + item, Toast.LENGTH_LONG).show();

    }

    public void onNothingSelected(AdapterView<?> arg0) {
        // TODO Auto-generated method stub

    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub

        new CreateUser().execute();

    }

    class CreateUser extends AsyncTask<String, String, String> {


        // Proceso de añadir alumno (esperando)
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(Insertar.this);
            pDialog.setMessage("A\u00f1adiendo alumno...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            // TODO Auto-generated method stub
            // Check for success tag
            int success;
            // Declaramos y asignamos el valor introducido a las variables
            String nombre = name.getText().toString();
            String apellido = lastname.getText().toString();
            String nota = note.getText().toString();
            try {
                // Building Parameters
                // ArrayList con los valores introducidos para enviar al PHP
                List params = new ArrayList();
                params.add(new BasicNameValuePair("nombre", nombre));
                params.add(new BasicNameValuePair("apellido", apellido));
                params.add(new BasicNameValuePair("nota", nota));
                params.add(new BasicNameValuePair("asignatura", opcionSeleccionada));

                Log.d("request!", "starting");

                //Posting user data to script
                // Enviamos al archivo PHP mediante POST los valores del último ArrayList
                JSONObject json = jsonParser.makeHttpRequest(
                        REGISTER_URL, "POST", params);

                // full json response
                Log.d("Registering attempt", json.toString());

                // json success element
                success = json.getInt(TAG_SUCCESS);
                if (success == 1) {
                    Log.d("Alumno a\u00f1adido", json.toString());
                    //finish();
                    // Mostramos el mensaje
                    return json.getString(TAG_MESSAGE);
                }else {
                    Log.d("No se ha podido a\u00f1adir", json.getString(TAG_MESSAGE));
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
            if (file_url != null){
                Toast.makeText(Insertar.this, file_url, Toast.LENGTH_LONG).show();
            }
        }
    }
}

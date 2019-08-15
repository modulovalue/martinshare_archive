package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.eintragen.Eintragen;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.update.UpdateEintraege;

/**
 * Created by Modestas Valauskas on 27.12.2014.
 */
public class NeuerEintragMOD extends Fragment {

    public String date, sqlDate;
    public TextView titelTV;
    public com.melnykov.fab.FloatingActionButton hBtn, aBtn, sBtn;

    public static NeuerEintragMOD newInstance( String beauDate, String sqlDate) {
        NeuerEintragMOD myFragment = new NeuerEintragMOD();
        Bundle args = new Bundle();

        args.putString("date", beauDate);
        args.putString("sqldate", sqlDate);
        myFragment.setArguments(args);

        return myFragment;
    }

    public View onCreateView(LayoutInflater inflater, @Nullable final ViewGroup container, @Nullable final Bundle savedInstanceState) {
        final View view = inflater.inflate(R.layout.mod_neuer_eintrag_fragment, container, false);
        titelTV = (TextView) view.findViewById(R.id.titelTextNeuerEintrag);
        titelTV.setText(date);

        hBtn = (com.melnykov.fab.FloatingActionButton) view.findViewById(R.id.eintragH);
        aBtn = (com.melnykov.fab.FloatingActionButton) view.findViewById(R.id.eintragA);
        sBtn = (com.melnykov.fab.FloatingActionButton) view.findViewById(R.id.eintragS);

        hBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                neuerEintragIntent("h");
            }
        });
        aBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                neuerEintragIntent("a");
            }
        });
        sBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                neuerEintragIntent("s");
            }
        });
        return view;
    }

    public void neuerEintragIntent(final String typ) {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Intent eintragIntent = new Intent(getActivity(), Eintragen.class);
                eintragIntent.putExtra("SQLDatum", sqlDate);
                eintragIntent.putExtra("anzeigeDatum", date);
                eintragIntent.putExtra("typ", typ);
                startActivityForResult(eintragIntent, 0);
            }
        });
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        date = getArguments().getString("date");
        sqlDate = getArguments().getString("sqldate");
    }

}

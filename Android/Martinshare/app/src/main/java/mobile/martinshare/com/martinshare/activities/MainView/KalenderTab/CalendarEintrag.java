package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.WholeEintragFragment;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.update.UpdateEintraege;

/**
 * Created by Modestas Valauskas on 27.12.2014.
 */
public class CalendarEintrag extends Fragment {
    private EintragObj eintrag;

    public TextView fachnametv,beschreibungtv;
    private ImageView eintragImage;
    private ImageView editImage;
    private LinearLayout eintragLayout;
    DialogFragment newFragment;

    public View onCreateView(LayoutInflater inflater, @Nullable final ViewGroup container, @Nullable final Bundle savedInstanceState) {
        final View view = inflater.inflate(R.layout.calendar_eintrag_layout, container, false);

        fachnametv = (TextView) view.findViewById(R.id.name_text);
        beschreibungtv = (TextView) view.findViewById(R.id.beschreibung_text);
        eintragImage = (ImageView) view.findViewById(R.id.eintragImageTyp);
        editImage = (ImageView) view.findViewById(R.id.editIcon);
        eintragLayout = (LinearLayout) view.findViewById(R.id.eintragLayout);


        fachnametv.setText(eintrag.getName());
        beschreibungtv.setText(eintrag.getBeschreibung());
        eintragImage.setImageResource(eintrag.getImage());

        if(eintrag.isDeletable()) {
            editImage.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent updateIntent = new Intent(getActivity().getBaseContext(), UpdateEintraege.class);
                    Bundle bundle = new Bundle();
                    bundle.putParcelable("eintrag", eintrag);
                    updateIntent.putExtras(bundle);
                    startActivityForResult(updateIntent, 0);
                    }
            });
        } else {
            editImage.setImageResource(R.drawable.trash);
        }


        eintragLayout.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                ((MainActivity) getActivity()).showOverviewFragment(eintrag);
                newFragment = WholeEintragFragment.newInstance(eintrag);
            }

        });
        return view;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        eintrag = getArguments().getParcelable("eintrag");
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent intent) {
        super.onActivityResult(requestCode, resultCode, intent);

        if (resultCode == MainActivity.AKTUALISIERENINTENT) {

            if(newFragment != null) {
                newFragment.dismissAllowingStateLoss();
            }
            getActivity().setResult(MainActivity.AKTUALISIERENINTENT);
            getActivity().finish();
        }
    }
}

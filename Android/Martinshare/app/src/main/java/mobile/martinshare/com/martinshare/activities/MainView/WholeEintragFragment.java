package mobile.martinshare.com.martinshare.activities.MainView;

import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.protocols.DeleteEintragProtocol;
import mobile.martinshare.com.martinshare.API.protocols.GetEintraegeProtocol;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.update.UpdateEintraege;
import mobile.martinshare.com.martinshare.activities.versionhistory.VersionHistory;


public class WholeEintragFragment extends DialogFragment implements DeleteEintragProtocol {


    private TextView datumtv, nametv, beschreibungtv;
    private ImageView typiv;
    private Button shareBtn;
    private Button okBtn;
    private Button editBtn;
    private Button deleteBtn;
    private Button historyBtn;

    private EintragObj eintrag;

    static public boolean dontAllowAnything = false;

    private SweetAlertDialog pDialog;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        eintrag = getArguments().getParcelable("eintrag");
        super.onCreate(savedInstanceState);
        VersionHistory.eintragObj = eintrag;
        pDialog = new SweetAlertDialog(getContext(), SweetAlertDialog.PROGRESS_TYPE);

        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        pDialog.setTitleText("Wird gelöscht");
        pDialog.setContentText("Bitte warten").setCancelable(false);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        pDialog.dismiss();
    }

    public static WholeEintragFragment newInstance(EintragObj eintrag) {
        WholeEintragFragment fragment = new WholeEintragFragment();
        Bundle bundle = new Bundle();
        bundle.putParcelable("eintrag", eintrag);
        fragment.setArguments(bundle);
        return fragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        getDialog().setCanceledOnTouchOutside(true);
        getDialog().getWindow().requestFeature(Window.FEATURE_NO_TITLE);

        final View view = inflater.inflate(R.layout.whole_eintrag_fragment, container, false);

        datumtv = (TextView) view.findViewById(R.id.datum_text);
        nametv = (TextView) view.findViewById(R.id.name_text);
        beschreibungtv = (TextView) view.findViewById(R.id.beschreibung_text);
        typiv = (ImageView) view.findViewById(R.id.eintragImageTyp);
        editBtn = (Button) view.findViewById(R.id.editView);
        shareBtn = (Button) view.findViewById(R.id.shareView);
        okBtn = (Button) view.findViewById(R.id.closeBtn);
        deleteBtn = (Button) view.findViewById(R.id.deleteBtn);
        historyBtn = (Button) view.findViewById(R.id.historyBtn);

        if(!dontAllowAnything) {
            if(!eintrag.isDeletable()) {
                deleteBtn.setEnabled(false);
                editBtn.setEnabled(false);
                shareBtn.setEnabled(false);
            } else {
                deleteBtn.setEnabled(true);
                editBtn.setEnabled(true);
                shareBtn.setEnabled(true);
            }

            historyBtn.setText(historyBtn.getText() + " (" + eintrag.getVersion() + ")");

            if(!eintrag.firstVersion()) {
                historyBtn.setVisibility(View.VISIBLE);
            } else {

                historyBtn.setVisibility(View.GONE);
            }

            historyBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(getContext(), VersionHistory.class);
                    startActivityForResult(intent, 0);
                }
            });

            deleteBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {

                    pDialog = new SweetAlertDialog(getActivity(), SweetAlertDialog.WARNING_TYPE);
                    pDialog.setTitleText("Wirklich löschen?")
                           .setContentText("Möchtest du diesen Eintrag löschen? Er kann danach nicht mehr bearbeitet werden.")
                           .setCancelText("Nicht löschen")
                           .setConfirmText("Löschen")
                           .showCancelButton(true)
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    MartinshareApiRetro.deleteEintrag(getActivity(), WholeEintragFragment.this, eintrag);
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .setCancelClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });

        } else {
            deleteBtn.setEnabled(false);
            editBtn.setEnabled(false);
            shareBtn.setEnabled(false);
            historyBtn.setVisibility(View.GONE);
            dontAllowAnything = false;
        }


        nametv.setText(eintrag.getName());
        beschreibungtv.setText(eintrag.getBeschreibung());
        typiv.setImageResource(eintrag.getImage());
        datumtv.setText(eintrag.getBeauDatum());

        okBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                getDialog().dismiss();
            }
        });

        editBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent updateIntent = new Intent(getActivity().getBaseContext(), UpdateEintraege.class);
                Bundle bundle = new Bundle();
                bundle.putParcelable("eintrag", eintrag);
                updateIntent.putExtras(bundle);
                startActivityForResult(updateIntent, 0);
            }
        });

        shareBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent sendIntent = new Intent();
                sendIntent.setAction(Intent.ACTION_SEND);
                sendIntent.putExtra(Intent.EXTRA_TEXT, eintrag.toString());
                sendIntent.setType("text/plain");
                startActivity(Intent.createChooser(sendIntent, eintrag.getTypAusgeschrieben() + " Teilen"));
            }
        });

        return view;
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    @Override
    public void startedDeleting() {
        pDialog.changeAlertType(SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        pDialog.setTitleText("Eintrag wird gelöscht");
        pDialog.setCancelable(false);
        pDialog.showCancelButton(false);
        pDialog.setContentText("");
        pDialog.show();
    }

    @Override
    public void deleted() {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.setTitleText("Gelöscht!")
                        .setContentText("Der Eintrag wurde gelöscht")
                        .setConfirmText("OK")
                        .showCancelButton(false)
                        .setCancelClickListener(null)
                        .setConfirmClickListener(null)
                        .changeAlertType(SweetAlertDialog.SUCCESS_TYPE);


                Toast.makeText(getContext(), "Eintrag gelöscht! ", Toast.LENGTH_SHORT).show();

                if(getActivity() instanceof MainActivity) {

                    pDialog.hide();
                    getActivity().getSupportFragmentManager().beginTransaction().remove(WholeEintragFragment.this).commit();
                    MainActivity activity = (MainActivity) getActivity();
                    Intent intent = new Intent();
                   // TODO DELETE IF NOT NEEDED CHECK IF THE METHOD BELOW FIRES  ---  MartinshareApiRetro.downloadEintraege(activity, ((MainActivity) getActivity()).getEintraegeProtocol, true);
                    ((MainActivity) getActivity()).onActivityResult(0, MainActivity.AKTUALISIERENINTENT, intent);
                }
            }
        });
    }

    @Override
    public void isNotLoggedIn() {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getContext(), "Du bist nicht eingeloogt ", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void noInternet() {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getContext(), "Keine Verbindung ", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void unknownError(final String error) {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getContext(),"Martinshare Meldet: " + error, Toast.LENGTH_LONG).show();
            }
        });
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }
}

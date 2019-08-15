package mobile.martinshare.com.martinshare.activities.MainView.EinstellungenTab;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.ShareCompat;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AlertDialog;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.text.InputType;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.protocols.FeedbackProtocol;
import mobile.martinshare.com.martinshare.API.protocols.LogoutProtocol;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.info.InfoActivity;
import mobile.martinshare.com.martinshare.activities.login.LoginScreen;
import mobile.martinshare.com.martinshare.activities.MainView.MenuOptionsDesigner;
import mobile.martinshare.com.martinshare.activities.stundenplan.ImageStorage;
import mobile.martinshare.com.martinshare.activities.stundenplan.StundenplanActivity;

/**
 * Created by Modestas Valauskas on 08.02.2016.
 */
public class EinstellungenTab extends Fragment implements RecyclerViewClickListener, MenuOptionsDesigner {

    RecyclerView list;

    public static String feedbackNachricht = "";

    SweetAlertDialog pDialog;

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        pDialog.dismiss();
    }

    List<Object> items;

    @Override
    public void recyclerViewListClicked(View v, int position, int type) {
        if(type == CustomListAdapter.TYPE_CELL) {
            ((ListItemCustomList) items.get(position)).runnable.run();
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.tab_einstellungen, container, false);

        pDialog = new SweetAlertDialog(getActivity(), SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        list = (RecyclerView) view.findViewById(R.id.listvieweinstellungen);
        list.setHasFixedSize(true);

        LinearLayoutManager llm = new LinearLayoutManager(getActivity());
        llm.setOrientation(LinearLayoutManager.VERTICAL);
        list.setLayoutManager(llm);

        items = new ArrayList<>();

        items.add("Benutzer");

        items.add(new ListItemCustomList(
                "Benutzer",
                Prefs.getUsername(getActivity()),
                R.drawable.weightlifting,
                new Runnable() {
            @Override
            public void run() {
                //
            }
        }));

        items.add(new ListItemCustomList(
                "Abmelden",
                "Melde dich ab",
                R.drawable.logout,
                new Runnable() {
                    @Override
                    public void run() {
                        MartinshareApiRetro.logout(getContext(), new LogoutProtocol() {
                        @Override
                        public void startedLogout() {
                            pDialog.setTitleText("Du wirst Ausgeloggt ...");
                            pDialog.setContentText("Bitte warten").setCancelable(false);
                            pDialog.show();
                        }

                        @Override
                        public void loggedOut() {
                            pDialog.hide();
                            Intent broadcastIntent = new Intent();
                            ImageStorage.deleteImage(StundenplanActivity.STUNDENPLANIMAGENAME);
                            broadcastIntent.setAction("com.martinshare.closeActivities");
                            getActivity().sendBroadcast(broadcastIntent);
                            Intent intent = new Intent(getActivity(), LoginScreen.class);
                            getActivity().startActivity(intent);
                        }

                        @Override
                        public void noInternetConnection() {
                            pDialog.hide();
                            Toast.makeText(getContext(), "Keine Internetverbindung", Toast.LENGTH_SHORT).show();
                        }

                        @Override
                        public void unknownError() {
                            Toast.makeText(getContext(), "Unbekannter Fehler", Toast.LENGTH_SHORT).show();
                            pDialog.hide();
                        }
                    });
                    }
                }));

        items.add("Kontakt");

        items.add(new ListItemCustomList(
                "Kontakt",
                "Wir beantworten gerne deine Fragen",
                R.drawable.email,
                new Runnable() {
                    @Override
                    public void run() {
                        ShareCompat.IntentBuilder builder = ShareCompat.IntentBuilder.from(getActivity());
                        builder.setType("message/rfc822");
                        builder.addEmailTo("info@martinshare.com");
                        builder.setSubject("Info");
                        builder.setText("\n \n \n \n Martinshare - " + Prefs.getUsername(getContext()) + " - Android");
                        builder.setChooserTitle("Martinshare E-Mail senden");
                        builder.startChooser();
                    }
                }));

        items.add(new ListItemCustomList(
                "Instant Feedback",
                "Teile uns deine Ideen und Wünsche mit!",
                R.drawable.instantfeedback,
                new Runnable() {
                    @Override
                    public void run() {
                        AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                        builder.setTitle("Sende Martinshare Feedback");
                        builder.setMessage("Hast du Probleme mit Martinshare?\nVorschläge?\nAnregungen?\nTeile sie uns mit! \n(max. 500 Zeichen)");

                        // Set up the input
                        final EditText input = new EditText(getContext());
                        input.setText(EinstellungenTab.feedbackNachricht);
                        input.setHint("Nachricht");
                        // Specify the type of input expected; this, for example, sets the input as a password, and will mask the text
                        input.setInputType(InputType.TYPE_CLASS_TEXT);
                        builder.setView(input);

                        // Set up the buttons
                        builder.setPositiveButton("Senden", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                EinstellungenTab.feedbackNachricht = input.getText().toString();
                                MartinshareApiRetro.sendfeedback(getContext(), new FeedbackProtocol() {
                                    @Override
                                    public void startedFeedback() {
                                        getActivity().runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                pDialog.setTitleText("Deine Nachricht wird übermittelt ...");
                                                pDialog.setContentText("Bitte warten").setCancelable(false);
                                                pDialog.show();
                                            }
                                        });
                                    }

                                    @Override
                                    public void feedbacksent() {
                                        EinstellungenTab.feedbackNachricht = "";
                                        getActivity().runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {

                                                pDialog.setTitleText("Super!")
                                                        .setContentText("Deine Nachricht wurde erfolgreich übermittelt")
                                                        .setConfirmText("OK")
                                                        .showCancelButton(false)
                                                        .setCancelClickListener(null)
                                                        .setConfirmClickListener(null)
                                                        .changeAlertType(SweetAlertDialog.SUCCESS_TYPE);

                                            }
                                        });
                                    }

                                    @Override
                                    public void isNotLoggedIn() {
                                        getActivity().runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                pDialog.hide();
                                                Toast.makeText(getContext(), "Du bist nicht eingeloggt ", Toast.LENGTH_SHORT).show();
                                            }
                                        });
                                    }

                                    @Override
                                    public void noInternet() {
                                        getActivity().runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                pDialog.hide();
                                                Toast.makeText(getContext(), "Es besteht keine Internetverbindung", Toast.LENGTH_SHORT).show();
                                            }
                                        });
                                    }

                                    @Override
                                    public void unknownError(final String error) {
                                        getActivity().runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                pDialog.hide();
                                                Toast.makeText(getContext(), error, Toast.LENGTH_LONG).show();
                                            }
                                        });
                                    }
                                }, input.getText().toString());
                            }
                        });
                        builder.setNegativeButton("Später", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                EinstellungenTab.feedbackNachricht = input.getText().toString();
                                dialog.cancel();
                            }
                        });

                        AlertDialog dialog = builder.show();

                        TextView messageView = (TextView) dialog.findViewById(android.R.id.message);
                        messageView.setGravity(Gravity.CENTER);
                    }
                }));


        //items.add("Erinnerungen");

        //items.add(new ListItemCustomList(
        //        "Erinnerungen",
        //        "Einstellungen für die Erinnerungen",
        //        R.drawable.erinnerungen,
        //        new Runnable() {
        //            @Override
        //            public void run() {
        //                getActivity().startActivity(new Intent(getActivity(), AppPreference.class));
        //            }
        //        }));


        items.add("Info");

        items.add(new ListItemCustomList(
                "Bewerte Martinshare",
                "Wir würden uns über eine Bewertung freuen!",
                R.drawable.heart,
                new Runnable() {
                    @Override
                    public void run() {
                        Intent intent = new Intent(Intent.ACTION_VIEW);
                        intent.setData(Uri.parse("market://details?id=mobile.martinshare.com.martinshare"));
                        startActivity(intent);
                    }
                }));
        items.add(new ListItemCustomList(
                "Info",
                "",
                R.drawable.info,
                new Runnable() {
                    @Override
                    public void run() {
                        getActivity().startActivityForResult(new Intent(getActivity(), InfoActivity.class), 0);
                    }
                }));
        list.setAdapter(new CustomListAdapter(items, this));

        return view;
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    public class ListItemCustomList {
        public String title;
        public String subtitle;
        public int imgresid;
        public Runnable runnable;

        public ListItemCustomList(String title, String subtitle, int imgresid, Runnable runnable) {
            this.title = title;
            this.subtitle = subtitle;
            this.imgresid = imgresid;
            this.runnable = runnable;
        }
    }

    @Override
    public void createOptionsMenu(ActionBar actionBar, Menu menu, MenuInflater menuInflater) {
        actionBar.setTitle("Einstellungen");
        actionBar.setSubtitle("");
        menuInflater.inflate(R.menu.menu_main, menu);
        menu.getItem(0).setIcon(R.drawable.ic_action_refresh);
    }

    @Override
    public void optionsItemSelected(MenuItem menuItem) {

    }
}


class CustomListAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    public List<Object> items;
    public static RecyclerViewClickListener recyclerViewClickListener;

    public CustomListAdapter(List<Object> items, RecyclerViewClickListener recyclerViewClickListener) {
        this.items = items;
        CustomListAdapter.recyclerViewClickListener = recyclerViewClickListener;

    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView;

        if(TYPE_HEADER == viewType) {
            itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_singleheader, parent, false);
            return new ListViewHeaderHolder(itemView);
        } else { //if(viewType == 1)
            itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_single, parent, false);
            return new ListViewHolder(itemView);
        }
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        switch (holder.getItemViewType()) {
            case TYPE_HEADER:
                ListViewHeaderHolder hHolder = (ListViewHeaderHolder) holder;
                String string = ((String) items.get(position));
                hHolder.txtTitle.setText(string);
                break;

            case TYPE_CELL:
                ListViewHolder lHolder = (ListViewHolder) holder;
                EinstellungenTab.ListItemCustomList customList = ((EinstellungenTab.ListItemCustomList) items.get(position));

                lHolder.txtTitle.setText(customList.title);
                lHolder.txtSubtitle.setText(customList.subtitle);
                lHolder.imageView.setImageResource(customList.imgresid);
                break;
        }
    }

    public static final int TYPE_HEADER = 1;
    public static final int TYPE_CELL = 2;

    @Override
    public int getItemViewType(int position) {
        if(position == 0) {
            return  TYPE_HEADER;
        } else if(position == 3) {
            return  TYPE_HEADER;
        } else if(position == 6) {
            return  TYPE_HEADER;
        }// else if(position == 8) {
         //   return  TYPE_HEADER;
       // }
        return TYPE_CELL;
    }

    @Override
    public int getItemCount() {
        return items.size() ;
    }

    public static class ListViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener{
        TextView txtTitle;
        TextView txtSubtitle;
        ImageView imageView;

        public ListViewHolder(View v) {
            super(v);
            txtTitle = (TextView) v.findViewById(R.id.plantitle);
            txtSubtitle = (TextView) v.findViewById(R.id.plansubtitle);
            imageView = (ImageView) v.findViewById(R.id.planimg);
            v.setOnClickListener(this);
        }

        @Override
        public void onClick(View v) {
            CustomListAdapter.recyclerViewClickListener.recyclerViewListClicked(v, this.getLayoutPosition(), this.getItemViewType());
        }
    }

    public static class ListViewHeaderHolder extends RecyclerView.ViewHolder{
        TextView txtTitle;
        public ListViewHeaderHolder(View v) {
            super(v);
            txtTitle = (TextView) v.findViewById(R.id.headertitle);
        }

    }


}
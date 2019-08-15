package mobile.martinshare.com.martinshare.API;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.preference.PreferenceManager;
import android.widget.ImageView;
import android.widget.Toast;
import com.android.volley.NetworkResponse;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.ImageRequest;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.squareup.okhttp.OkHttpClient;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.API.POJO.SuggestionPojo;
import mobile.martinshare.com.martinshare.API.POJO.User;
import mobile.martinshare.com.martinshare.API.protocols.*;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.MainView.KalenderTab.KalenderTab;
import mobile.martinshare.com.martinshare.activities.MainView.UebersichtTab.UebersichtTab;
import mobile.martinshare.com.martinshare.activities.eintragen.AutocompleteSuggestions;
import mobile.martinshare.com.martinshare.activities.eintragen.EintragSimple;
import mobile.martinshare.com.martinshare.activities.stundenplan.ImageStorage;
import mobile.martinshare.com.martinshare.activities.stundenplan.StundenplanActivity;
import mobile.martinshare.com.martinshare.activities.vertretungsplan.VertretungsplanData;
import org.json.JSONException;
import org.json.JSONObject;
import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.OkClient;
import retrofit.client.Response;
import retrofit.converter.ConversionException;
import retrofit.converter.Converter;
import retrofit.mime.TypedInput;
import retrofit.mime.TypedOutput;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.concurrent.TimeUnit;

/**
 * Created by Modestas Valauskas on 11.04.2015.
 */
public class MartinshareApiRetro {


    private static String ROOT =
            "https://www.martinshare.com/api/api.php";

    private static RestAdapter restAdapter;

    public static MartinshareMobileApi getRestAdapter() {
        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);


        if(restAdapter == null) {
            restAdapter =  new RestAdapter.Builder()
                    .setEndpoint(ROOT)
                    .setClient(new OkClient(okHttpClient))
                    .build();
        }
        return restAdapter.create(MartinshareMobileApi.class);
    }

    public static void login(String username, final String password, final String key, final Context con, final LoginProtocol login) {
        login.startedLogingIn();


        getRestAdapter().login(username, password, key, "android", "0", new Callback<User>() {

            @Override
            public void failure(RetrofitError error) {

            }

            @Override
            public void success(User user, retrofit.client.Response response) {
                login.rightCredentials();
                Prefs.saveUsername(user.getUsername(), con);
                Prefs.saveKey(user.getKey(), con);
            }
        });
    }

    public static void getStundenPlan(final ImageView mImageView, final Context context, final RequestInterfaceHandle handle) {
        handle.onStartedRequest();
        RestAdapter restAdapter = new RestAdapter.Builder()
                .setConverter(new StringConverter())
                .setEndpoint(ROOT)
                .build();

        restAdapter.create(MartinshareMobileApi.class).getStundenplan(Prefs.getUsername(context), Prefs.getKey(context), new Callback<String>() {

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    handle.onError(error.getResponse().getStatus());
                } else {
                    handle.onError(0);
                }
            }

            @Override
            public void success(String str, Response ignored) {
                ImageRequest request = new ImageRequest(str,
                        new com.android.volley.Response.Listener<Bitmap>() {
                            @Override
                            public void onResponse(Bitmap bitmap) {
                                ImageStorage.deleteImage(StundenplanActivity.STUNDENPLANIMAGENAME);
                                ImageStorage.saveToSdCard(bitmap, StundenplanActivity.STUNDENPLANIMAGENAME);
                                mImageView.setImageBitmap(bitmap);
                                handle.onSucess();
                            }
                        }, 0, 0, null,
                        new com.android.volley.Response.ErrorListener() {
                            public void onErrorResponse(VolleyError error) {
                                NetworkResponse networkResponse = error.networkResponse;
                                if (networkResponse != null) {
                                    handle.onError(networkResponse.statusCode);
                                }
                                handle.onError(0);
                            }
                        });
                VolleySingleton.getInstance().getRequestQueue().add(request);
            }
        });
    }

    public static void logout(final Context context, final LogoutProtocol handle) {
        handle.startedLogout();
        getRestAdapter().logout(Prefs.getUsername(context), Prefs.getKey(context), new Callback<String>() {
            @Override
            public void success(String s, retrofit.client.Response response) {
                handle.loggedOut();

                PreferenceManager.getDefaultSharedPreferences(context).edit().clear().commit();
                context.getSharedPreferences(Prefs.PREF_FILE_NAME,0).edit().clear().commit();

                Prefs.savePushRegID(Prefs.standartPushRegID, context);
                Prefs.savePushAllow(Prefs.standartPushAllow, context);
                Prefs.serverHasRightPush(false, context);
                Prefs.saveKey(Prefs.standartKey, context);
                Prefs.saveUsername(Prefs.standartUsername, context);
                Prefs.saveLastChanged(Prefs.standartLastChanged, context);
            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    switch (error.getResponse().getStatus()) {
                        case 403:
                            handle.loggedOut();
                            break;
                        default:
                            handle.unknownError();
                            break;
                    }
                } else {
                    handle.noInternetConnection();
                }
            }
        });
    }

    public static void downloadEintraege(final Activity activity, final GetEintraegeProtocol handle, final boolean warn) {
        handle.startedGetting();

        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);

        RestAdapter restAdapter = new RestAdapter.Builder()
            .setConverter(new StringConverter())
            .setEndpoint(ROOT)
            .setClient(new OkClient(okHttpClient))
            .build();

        restAdapter.create(MartinshareMobileApi.class).geteintraege(Prefs.getUsername(activity), Prefs.getKey(activity), Prefs.getLastChanged(activity), new Callback<String>() {
            @Override
            public void success(String s, retrofit.client.Response response) {


                if (1 == Integer.parseInt(getHeader(response, "Haschanged"))) {

                    Type collectionType = new TypeToken<EintraegeList>() { }.getType();

                    EintraegeList eintraege = new Gson().fromJson(s, collectionType);
                    Prefs.witeObjectToFile(activity, eintraege, EintraegeList.fileName);

                    Prefs.eintragObjs = eintraege;
                    UebersichtTab.refresh = true;
                    KalenderTab.refresh = true;
                    handle.aktualisiert(warn);

                } else {
                    Type collectionType = new TypeToken<EintraegeList>() {
                    }.getType();
                    EintraegeList eintraege = new Gson().fromJson(s, collectionType);
                    Prefs.witeObjectToFile(activity, eintraege, EintraegeList.fileName);
                    Prefs.eintragObjs = eintraege;

                    handle.notChanged(warn);
                }
                Prefs.saveLastChanged(getHeader(response, "Letztesupdate"), activity);
            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    handle.notLoggedIn();
                } else {
                    handle.noInternet();
                }
            }
        });
    }

    public static void getActivität(final Activity activity, final GetActivityProtocol handle, final boolean warn) {
        handle.startedGetting();

        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setConverter(new StringConverter())
                .setEndpoint(ROOT)
                .setClient(new OkClient(okHttpClient))
                .build();

        restAdapter.create(MartinshareMobileApi.class).getEreignisse(Prefs.getUsername(activity), Prefs.getKey(activity), new Callback<String>() {
            @Override
            public void success(final String s, retrofit.client.Response response) {
                handle.aktualisiert(warn, s);
            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    switch (error.getResponse().getStatus()) {
                        case 403:
                            handle.notLoggedIn();
                            break;
                        default:
                            handle.unknownError();
                            break;
                    }
                } else {
                    handle.noInternet();
                }
            }

        });
    }


    public static void getNameSuggestions(final Context context, final Activity activity, String datum, String countlet, final AutocompleteSuggestions sug) {

        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setConverter(new StringConverter())
                .setEndpoint(ROOT)
                .setClient(new OkClient(okHttpClient))
                .build();

        restAdapter.create(MartinshareMobileApi.class).getnamesuggestions(Prefs.getUsername(context), Prefs.getKey(context), datum, countlet, new Callback<String>() {
            @Override
            public void success(String s, final retrofit.client.Response response) {

                SuggestionPojo suggestions = new Gson().fromJson(s, new TypeToken<SuggestionPojo>() {
                }.getType());
                if(suggestions == null) {

                } else {
                    sug.fill(suggestions);
                }
            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    //handle.notLoggedIn();
                    Toast.makeText(context, "Nicht eingeloggt?", Toast.LENGTH_SHORT).show();
                } else {
                    //handle.noInternet();
                    Toast.makeText(context, "Keine Internetverbindung?", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }


    public static void downloadVersionHistory(final Context context, final GetVersionHistoryProtocol handle, EintragObj eintragObj) {

        handle.startedGetting();

        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setConverter(new StringConverter())
                .setEndpoint(ROOT)
                .setClient(new OkClient(okHttpClient))
                .build();

        restAdapter.create(MartinshareMobileApi.class).versionHistory(Prefs.getUsername(context), Prefs.getKey(context), eintragObj.getId(), new Callback<String>() {
            @Override
            public void success(String s, retrofit.client.Response response) {

                Type collectionType = new TypeToken<EintraegeList>() {
                }.getType();

                EintraegeList eintraege = new Gson().fromJson(s, collectionType);

                handle.gotVersionHistory(eintraege);

            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {

                    switch (error.getResponse().getStatus()) {

                        case 403:
                            handle.notLoggedIn();
                            break;
                        case 409:
                            handle.unknownError("Martinshare meldet: " + getHeader(error.getResponse(), "Reason"));
                            break;
                        default:
                            handle.unknownError(error.getResponse().getStatus() + " ERROR");
                            break;
                    }

                } else {
                    handle.noInternet();
                }
            }
        });
    }

    public static void checkPush(String username, String key, String pushID, final Context context, final IsPushIDUp isPushIDUp) {

        getRestAdapter().checkPush(username, key, pushID, new Callback<String>() {
            @Override
            public void success(String s, Response response) {

                //if Haspushid != 0 then it was already ok
                if (0 == Integer.parseInt(getHeader(response, "Haspushid"))) {
                    isPushIDUp.pushIDWRONG(context);
                } else {
                    isPushIDUp.pushIDOK(context);
                }
            }

            @Override
            public void failure(RetrofitError error) {
                if (error.getResponse() != null) {
                    isPushIDUp.pushIDERROR(context);
                }
            }
        });

    }

    public static void isloggedin(String username, String key, final Context context, final IsLoggedInProtocol ili) {
        ili.startedChecking();

        if(ili.emptyCredentials(username,key)) {
            ili.neverWasLoggedIn();
        } else {
            getRestAdapter().isLoggedIn(Prefs.getUsername(context), Prefs.getKey(context), new Callback<String>() {
                @Override
                public void success(String s, Response response) {
                    ili.isLoggedIn();
                }

                @Override
                public void failure(RetrofitError error) {
                    if(error.getResponse() != null) {
                        switch ( error.getResponse().getStatus()) {
                            case 403:
                                ili.isNotLoggedIn();
                                break;
                        }
                    } else {
                        ili.isLoggedIn();
                    }
                }
            });
        }
    }

    public static void neuerEintrag(final Context context, final EintragenProtocol handle, final EintragSimple eintrag) {
        handle.startedEintragen();

        getRestAdapter().neuerEintrag(
                Prefs.getUsername(context),
                Prefs.getKey(context),
                eintrag.getArt(),
                eintrag.getFach(),
                eintrag.getBeschreibung(),
                eintrag.getDatum(),
                new Callback<String>() {

                    @Override
                    public void success(String s, Response response) {
                        handle.eingetragen();
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getResponse() != null) {
                            switch (error.getResponse().getStatus()) {
                                case 403:
                                    handle.isNotLoggedIn();
                                    break;
                                case 409:
                                    handle.unknownError("Martinshare meldet: " + getHeader(error.getResponse(), "Reason"));
                                    break;
                                default:
                                    handle.unknownError(error.getResponse().getStatus() + " ERROR");
                                    break;
                            }
                        } else {
                            handle.noInternet();
                        }
                    }
                });
    }

    public static void sendfeedback(final Context context, final FeedbackProtocol feedback, final String message) {
        feedback.startedFeedback();

        getRestAdapter().sendFeedback(
                Prefs.getUsername(context),
                Prefs.getKey(context),
                message,
                "android",
                new Callback<String>() {

                    @Override
                    public void success(String s, Response response) {
                        feedback.feedbacksent();
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getResponse() != null) {
                            switch (error.getResponse().getStatus()) {
                                case 403:
                                    feedback.isNotLoggedIn();
                                    break;
                                case 409:
                                    feedback.unknownError("Martinshare meldet: " + getHeader(error.getResponse(), "Reason"));
                                    break;
                                default:
                                    feedback.unknownError(error.getResponse().getStatus() + " ERROR");
                                    break;
                            }
                        } else {
                            feedback.noInternet();
                        }
                    }
                });
    }

    public static void updateEintrag(final Context context, final UpdateEintragProtocol handle, final EintragSimple eintrag) {
        handle.startedUpdating();
        getRestAdapter().updateEintrag(
                Prefs.getUsername(context),
                Prefs.getKey(context),
                eintrag.getId(),
                eintrag.getFach(),
                eintrag.getBeschreibung(),
                eintrag.getDatum(),
                eintrag.getArt(),
                new Callback<String>() {

                    @Override
                    public void success(String s, Response response) {
                        handle.upgedatet();
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getResponse() != null) {
                            switch (error.getResponse().getStatus()) {
                                case 403:
                                    handle.isNotLoggedIn();
                                    break;
                                case 409:
                                    handle.unknownError("Martinshare meldet: " + getHeader(error.getResponse(), "Reason"));
                                default:
                                    handle.unknownError(error.getResponse().getStatus() + " ERROR");
                                    break;
                            }
                        } else {
                            handle.noInternet();
                        }
                    }
                });
    }

    public static void deleteEintrag(final Context context, final DeleteEintragProtocol handle, final EintragObj eintrag) {
        handle.startedDeleting();
        getRestAdapter().deleteEintrag(
                Prefs.getUsername(context),
                Prefs.getKey(context),
                eintrag.getId(),
                new Callback<String>() {

                    @Override
                    public void success(String s, Response response) {
                        handle.deleted();
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getResponse() != null) {
                            switch (error.getResponse().getStatus()) {
                                case 403:
                                    handle.isNotLoggedIn();
                                    break;
                                case 409:
                                    handle.unknownError("Martinshare meldet: " + getHeader(error.getResponse(), "Reason"));
                                default:
                                    handle.unknownError( error.getResponse().getStatus() + " ERROR");
                                    break;
                            }
                        } else {
                            handle.noInternet();
                        }
                    }
                });
    }

    public static void getVetretungsplan(String username, String key, final GetVertretungsplanProtocol handle ) {

        final OkHttpClient okHttpClient = new OkHttpClient();
        okHttpClient.setReadTimeout(7, TimeUnit.SECONDS);
        okHttpClient.setConnectTimeout(7, TimeUnit.SECONDS);

        handle.startedGetting();

        final VertretungsplanData data = new VertretungsplanData();

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setConverter(new StringConverter())
                .setEndpoint(ROOT)
                .setClient(new OkClient(okHttpClient))
                .build();

        restAdapter.create(MartinshareMobileApi.class).getVertretungsplan(username, key, new Callback<String>() {

                    @Override
                    public void success(String s, Response response) {
                        if (getHeader(response, "Seiten") != null) {

                            int seiten = Integer.parseInt(getHeader(response, "Seiten"));

                            if (seiten > 0) {
                                ArrayList<String> urls = new ArrayList<String>();
                                try {
                                    JSONObject names = new JSONObject(s);
                                    data.setCountSeiten(seiten);
                                    for (int i = 2; i < seiten + 2; i++) {
                                        urls.add(
                                                getHeader(response, "Domain") +
                                                        getHeader(response, "Folder") +
                                                        getHeader(response, "Schule") + "/" +
                                                        names.getString(i + ""));
                                    }
                                    data.setUrls(urls);
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            } else {
                                data.setCountSeiten(0);
                                handle.keinePlaeneVorhanden();
                            }
                            handle.success(data);
                        }
                    }

                    @Override
                    public void failure(RetrofitError error) {
                        if (error.getResponse() != null) {
                            handle.wrongCredentials();
                        } else {
                            handle.noInternetConnection();
                        }
                    }
                }
        );

    }


    public static String getHeader(Response response, String name) {

        for(int i = 0; i < response.getHeaders().size(); i++) {
            if (response.getHeaders().get(i).getName().equals(name)) {
                return response.getHeaders().get(i).getValue();
            }
        }
        return null;
    }

}

class StringConverter implements Converter {

    @Override
    public Object fromBody(TypedInput typedInput, Type type) throws ConversionException {
        String text = null;
        try {
            text = fromStream(typedInput.in());
        } catch (IOException ignored) {/*NOP*/ }
        return text;
    }

    @Override
    public TypedOutput toBody(Object o) {
        return null;
    }

    public static String fromStream(InputStream in) throws IOException {
        BufferedReader reader = new BufferedReader(new InputStreamReader(in));
        StringBuilder out = new StringBuilder();
        String newLine = System.getProperty("line.separator");
        String line;
        while ((line = reader.readLine()) != null) {
            out.append(line);
            out.append(newLine);
        }
        return out.toString();
    }
}
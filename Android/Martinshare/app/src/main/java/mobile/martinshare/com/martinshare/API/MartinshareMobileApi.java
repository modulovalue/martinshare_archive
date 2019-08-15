package mobile.martinshare.com.martinshare.API;


import mobile.martinshare.com.martinshare.API.POJO.User;
import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.Headers;
import retrofit.http.POST;

/**
 * Created by Modestas Valauskas on 11.04.2015.
 */
public interface MartinshareMobileApi {

    @FormUrlEncoded
    @POST("/login/")
    public void login (@Field("username") String username, @Field("password") String password, @Field("key") String key, @Field("device") String device, @Field("pushid") String pushid, Callback<User> user );

    @FormUrlEncoded
    @POST("/getstundenplan/")
    public void getStundenplan (@Field("username") String username, @Field("key") String key, Callback<String> s );

    @FormUrlEncoded
    @POST("/logout/")
    public void logout (@Field("username") String username, @Field("key") String key, Callback<String> s );

    @FormUrlEncoded
    @POST("/geteintraege/")
    public void geteintraege (@Field("username") String username, @Field("key") String key, @Field("lastchanged") String lastchanged, Callback<String> s );

    @FormUrlEncoded
    @POST("/getactivity/")
    public void getEreignisse(@Field("username") String username,
                              @Field("key") String key,
                              Callback<String> s );

    @FormUrlEncoded
    @POST("/getnamesuggestion/")
    public void getnamesuggestions (@Field("username") String username,
                                    @Field("key") String key,
                                    @Field("date") String date,
                                    @Field("name") String let,
                                    Callback<String> s );

    @FormUrlEncoded
    @POST("/isloggedin/")
    public void isLoggedIn (@Field("username") String username, @Field("key") String key, Callback<String> s );


    @FormUrlEncoded
    @POST("/neuereintrag/")
    public void neuerEintrag (@Field("username") String username,
                              @Field("key") String key,
                              @Field("typ") String typ,
                              @Field("fach") String fach,
                              @Field("beschreibung") String beschreibung,
                              @Field("datum") String datum,
                              Callback<String> s );

    @FormUrlEncoded
    @POST("/sendfeedback/")
    public void sendFeedback (@Field("username") String username,
                              @Field("key") String key,
                              @Field("message") String message,
                              @Field("device") String datum,
                              Callback<String> s );

    @FormUrlEncoded
    @POST("/updateeintrag/")
    public void updateEintrag (@Field("username") String username,
                              @Field("key") String key,
                              @Field("id") String id,
                              @Field("fach") String fach,
                              @Field("beschreibung") String beschreibung,
                              @Field("datum") String datum,
                              @Field("typ") String typ,
                              Callback<String> s );

    @FormUrlEncoded
    @POST("/getversionhistory/")
    public void versionHistory (@Field("username") String username,
                               @Field("key") String key,
                               @Field("id") String id,
                               Callback<String> s );

    @FormUrlEncoded
    @POST("/deleteeintrag/")
    public void deleteEintrag (@Field("username") String username,
                               @Field("key") String key,
                               @Field("id") String id,
                               Callback<String> s );

    @FormUrlEncoded
    @POST("/getvertretungsplan/")
    public void getVertretungsplan (@Field("username") String username, @Field("key") String key, Callback<String> s );


    @FormUrlEncoded
    @POST("/checkpush/")
    public void checkPush (@Field("username") String username, @Field("key") String key,  @Field("pushkey") String pushid, Callback<String> s );



}

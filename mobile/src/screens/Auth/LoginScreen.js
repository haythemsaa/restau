/**
 * Login Screen
 */

import React, {useState} from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  ActivityIndicator,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import Toast from 'react-native-toast-message';
import {useAuth} from '../../context/AuthContext';
import {colors, typography, spacing, borderRadius} from '../../utils/theme';

const LoginScreen = ({navigation}) => {
  const {login} = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!email || !password) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Veuillez remplir tous les champs',
      });
      return;
    }

    setLoading(true);

    try {
      const result = await login(email, password);

      if (result.success) {
        Toast.show({
          type: 'success',
          text1: 'Connexion réussie',
          text2: 'Bienvenue sur RestauBoost!',
        });
      } else {
        Toast.show({
          type: 'error',
          text1: 'Erreur de connexion',
          text2: result.message || 'Email ou mot de passe incorrect',
        });
      }
    } catch (error) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Une erreur est survenue',
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}>
        {/* Header with Gradient */}
        <LinearGradient
          colors={colors.gradient.primary}
          style={styles.header}
          start={{x: 0, y: 0}}
          end={{x: 1, y: 1}}>
          <Icon name="restaurant" size={60} color={colors.white} />
          <Text style={styles.headerTitle}>RestauBoost</Text>
          <Text style={styles.headerSubtitle}>Marketing SaaS pour Restaurants</Text>
        </LinearGradient>

        {/* Login Form */}
        <View style={styles.formContainer}>
          <Text style={styles.title}>Connexion</Text>
          <Text style={styles.subtitle}>Accédez à votre compte</Text>

          {/* Email Input */}
          <View style={styles.inputContainer}>
            <Icon
              name="mail-outline"
              size={20}
              color={colors.gray}
              style={styles.inputIcon}
            />
            <TextInput
              style={styles.input}
              placeholder="Email"
              placeholderTextColor={colors.grayLight}
              value={email}
              onChangeText={setEmail}
              keyboardType="email-address"
              autoCapitalize="none"
              autoCorrect={false}
            />
          </View>

          {/* Password Input */}
          <View style={styles.inputContainer}>
            <Icon
              name="lock-closed-outline"
              size={20}
              color={colors.gray}
              style={styles.inputIcon}
            />
            <TextInput
              style={styles.input}
              placeholder="Mot de passe"
              placeholderTextColor={colors.grayLight}
              value={password}
              onChangeText={setPassword}
              secureTextEntry={!showPassword}
              autoCapitalize="none"
            />
            <TouchableOpacity
              onPress={() => setShowPassword(!showPassword)}
              style={styles.eyeIcon}>
              <Icon
                name={showPassword ? 'eye-outline' : 'eye-off-outline'}
                size={20}
                color={colors.gray}
              />
            </TouchableOpacity>
          </View>

          {/* Forgot Password */}
          <TouchableOpacity style={styles.forgotPassword}>
            <Text style={styles.forgotPasswordText}>Mot de passe oublié?</Text>
          </TouchableOpacity>

          {/* Login Button */}
          <TouchableOpacity
            style={styles.loginButton}
            onPress={handleLogin}
            disabled={loading}>
            <LinearGradient
              colors={colors.gradient.primary}
              style={styles.loginButtonGradient}
              start={{x: 0, y: 0}}
              end={{x: 1, y: 0}}>
              {loading ? (
                <ActivityIndicator color={colors.white} />
              ) : (
                <Text style={styles.loginButtonText}>Se connecter</Text>
              )}
            </LinearGradient>
          </TouchableOpacity>

          {/* Register Link */}
          <View style={styles.registerContainer}>
            <Text style={styles.registerText}>Pas encore de compte? </Text>
            <TouchableOpacity onPress={() => navigation.navigate('Register')}>
              <Text style={styles.registerLink}>S'inscrire</Text>
            </TouchableOpacity>
          </View>

          {/* Demo Credentials */}
          <View style={styles.demoContainer}>
            <Text style={styles.demoTitle}>🎯 Compte démo:</Text>
            <Text style={styles.demoText}>Email: demo@restauboost.com</Text>
            <Text style={styles.demoText}>Mot de passe: demo123</Text>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  scrollContent: {
    flexGrow: 1,
  },
  header: {
    paddingTop: 80,
    paddingBottom: 40,
    alignItems: 'center',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  headerTitle: {
    fontSize: typography.fontSize['3xl'],
    fontWeight: 'bold',
    color: colors.white,
    marginTop: spacing.base,
  },
  headerSubtitle: {
    fontSize: typography.fontSize.sm,
    color: colors.white,
    opacity: 0.9,
    marginTop: spacing.xs,
  },
  formContainer: {
    padding: spacing.xl,
  },
  title: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.text,
    marginBottom: spacing.xs,
  },
  subtitle: {
    fontSize: typography.fontSize.base,
    color: colors.textLight,
    marginBottom: spacing.xl,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.white,
    borderRadius: borderRadius.md,
    paddingHorizontal: spacing.base,
    marginBottom: spacing.base,
    borderWidth: 1,
    borderColor: colors.border,
  },
  inputIcon: {
    marginRight: spacing.sm,
  },
  input: {
    flex: 1,
    height: 50,
    fontSize: typography.fontSize.base,
    color: colors.text,
  },
  eyeIcon: {
    padding: spacing.sm,
  },
  forgotPassword: {
    alignSelf: 'flex-end',
    marginBottom: spacing.xl,
  },
  forgotPasswordText: {
    color: colors.primary,
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
  },
  loginButton: {
    borderRadius: borderRadius.md,
    overflow: 'hidden',
    marginBottom: spacing.base,
  },
  loginButtonGradient: {
    paddingVertical: spacing.base,
    alignItems: 'center',
    justifyContent: 'center',
    height: 50,
  },
  loginButtonText: {
    color: colors.white,
    fontSize: typography.fontSize.base,
    fontWeight: 'bold',
  },
  registerContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: spacing.base,
  },
  registerText: {
    color: colors.textLight,
    fontSize: typography.fontSize.sm,
  },
  registerLink: {
    color: colors.primary,
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
  },
  demoContainer: {
    marginTop: spacing.xl,
    padding: spacing.base,
    backgroundColor: colors.light,
    borderRadius: borderRadius.md,
    borderLeftWidth: 4,
    borderLeftColor: colors.primary,
  },
  demoTitle: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.xs,
  },
  demoText: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginTop: 2,
  },
});

export default LoginScreen;
